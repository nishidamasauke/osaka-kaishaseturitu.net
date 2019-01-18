window.requestAnimFrame = (function(callback) {
  return window.requestAnimationFrame ||
  window.webkitRequestAnimationFrame ||
  window.mozRequestAnimationFrame ||
  window.oRequestAnimationFrame ||
  window.msRequestAnimationFrame ||
  function(callback){
    return window.setTimeout(callback, 1000/60);
  };
})();

window.cancelAnimFrame = (function(_id) {
  return window.cancelAnimationFrame ||
  window.cancelRequestAnimationFrame ||
  window.webkitCancelAnimationFrame ||
  window.webkitCancelRequestAnimationFrame ||
  window.mozCancelAnimationFrame ||
  window.mozCancelRequestAnimationFrame ||
  window.msCancelAnimationFrame ||
  window.msCancelRequestAnimationFrame ||
  window.oCancelAnimationFrame ||
  window.oCancelRequestAnimationFrame ||
  function(_id) { window.clearTimeout(id); };
})();
var __extends = (this && this.__extends) || function (d, b) {
    for (var p in b) if (b.hasOwnProperty(p)) d[p] = b[p];
    function __() { this.constructor = d; }
    __.prototype = b.prototype;
    d.prototype = new __();
};
function closest(el, selector) {
  // type el -> Object
  // type select -> String
  var matchesFn;
  // find vendor prefix
  ['matches', 'webkitMatchesSelector', 'mozMatchesSelector', 'msMatchesSelector', 'oMatchesSelector'].some(function(fn) {
    if (typeof document.body[fn] == 'function') {
      matchesFn = fn;
      return true;
    }
    return false;
  })
  var parent;
  // traverse parents
  while (el) {
    parent = el.parentElement;
    if (parent && parent[matchesFn](selector)) {
      return parent;
    }
    el = parent;
  }
  return null;
}

function getCssProperty(elem, property) {
  return window.getComputedStyle(elem, null).getPropertyValue(property);
}
var transform = ["transform", "msTransform", "webkitTransform", "mozTransform", "oTransform"];
var flex = ['-webkit-box', '-moz-box', '-ms-flexbox', '-webkit-flex', 'flex'];
var fd = ['flexDirection', '-webkit-flexDirection', '-moz-flexDirection'];
var animatriondelay = ["animationDelay","-moz-animationDelay","-wekit-animationDelay"];
function getSupportedPropertyName(properties) {
  for (var i = 0; i < properties.length; i++) {
    if (typeof document.body.style[properties[i]] != "undefined") {
      return properties[i];
    }
  }
  return null;
}
var transformProperty = getSupportedPropertyName(transform);
var flexProperty = getSupportedPropertyName(flex);
var fdProperty = getSupportedPropertyName(fd);
var ad = getSupportedPropertyName(animatriondelay);
var easingEquations = {
  easeOutSine: function(pos) {
    return Math.sin(pos * (Math.PI / 2));
  },
  easeInOutSine: function(pos) {
    return (-0.5 * (Math.cos(Math.PI * pos) - 1));
  },
  easeInOutQuint: function(pos) {
    if ((pos /= 0.5) < 1) {
      return 0.5 * Math.pow(pos, 5);
    }
    return 0.5 * (Math.pow((pos - 2), 5) + 2);
  }
};

function isPartiallyVisible(el) {
  var elementBoundary = el.getBoundingClientRect();
  var top = elementBoundary.top;
  var bottom = elementBoundary.bottom;
  var height = elementBoundary.height;
  return ((top + height >= 0) && (height + window.innerHeight >= bottom));
}

function isFullyVisible(el) {
  var elementBoundary = el.getBoundingClientRect();
  var top = elementBoundary.top;
  var bottom = elementBoundary.bottom;
  return ((top >= 0) && (bottom <= window.innerHeight));
}

function CreateElementWithClass(elementName, className) {
  var el = document.createElement(elementName);
  el.className = className;
  return el;
}

function createElementWithId(elementName, idName) {
  var el = document.createElement(elementName);
  el.id = idName;
  return el;
}

function getScrollbarWidth() {
  var outer = document.createElement("div");
  outer.style.visibility = "hidden";
  outer.style.width = "100px";
  document.body.appendChild(outer);
  var widthNoScroll = outer.offsetWidth;
  // force scrollbars
  outer.style.overflow = "scroll";
  // add innerdiv
  var inner = document.createElement("div");
  inner.style.width = "100%";
  outer.appendChild(inner);
  var widthWithScroll = inner.offsetWidth;
  // remove divs
  outer.parentNode.removeChild(outer);
  return widthNoScroll - widthWithScroll;
}

function insertAfter(referenceNode, newNode) {
  referenceNode.parentNode.insertBefore(newNode, referenceNode.nextSibling);
}
var wordsToArray = function wordsToArray(str) {return str.split('').map(function (e) {return e === ' ' ? '&nbsp;' : e;});};

function insertSpan(elem, letters, startTime) {
  elem.textContent = ''
  var curr = 0
  var delay = 20
  letters.forEach(function(letter,i){
    var span = document.createElement('span');
    span.classList.add('waveText');
    span.innerHTML = letter
    span.style[ad] = (++curr / delay + (startTime || 0)) + 's'
    elem.appendChild(span)
  })
}
function getPosition(el) {
  var xPos = 0;
  var yPos = 0;
  while (el) {
    if (el.tagName == "BODY") {
      // deal with browser quirks with body/window/document and page scroll
      var xScroll = el.scrollLeft || document.documentElement.scrollLeft;
      var yScroll = el.scrollTop || document.documentElement.scrollTop;

      xPos += (el.offsetLeft - xScroll + el.clientLeft);
      yPos += (el.offsetTop - yScroll + el.clientTop);
    } else {
      // for all other non-BODY elements
      xPos += (el.offsetLeft - el.scrollLeft + el.clientLeft);
      yPos += (el.offsetTop - el.scrollTop + el.clientTop);
    }
    el = el.offsetParent;
  }
  return {
    x: xPos,
    y: yPos
  };
}

// if ((navigator.userAgent.indexOf('iPhone') > 0 && navigator.userAgent.indexOf('iPad') == -1) || navigator.userAgent.indexOf('iPod') > 0 || (navigator.userAgent.indexOf('Android') > 0 && navigator.userAgent.indexOf('Mobile') > 0)) {
//   var url = window.location.pathname;
//   location.href = '/sp'+url;
// }

window.addEventListener('DOMContentLoaded', function() {
  new Effect();
  new Menu();
  new Sticky();
  new Anchor();
  new PageTop();
});
window.addEventListener('load',function(){
  new Load();
})

var Effect = (function(){
  function Effect(){
    var e = this;
    this.eles = document.querySelectorAll('.effect');
    this.ptop = document.getElementById('pagetop');
    this.handling = function(){
      var _top  = document.documentElement.scrollTop
      Array.prototype.forEach.call(e.eles,function(el,i){
        if(isPartiallyVisible(el)){
          el.classList.add('active');
          e.ptop.classList.add('active');
        }
      })
    }
    window.addEventListener('scroll',e.handling,false);
    this.handling();
  }
  return Effect;
})()

var Menu = (function(){
  function Menu(){
    var m = this;
    this._target = document.getElementById('icon_nav');
    this._mobile = document.getElementById('nav');
    this._header = document.getElementById('header');
    this._target.addEventListener('click',function(){
      if(this.classList.contains('open')){
        this.classList.remove('open');
        m._mobile.classList.remove('open');
        m._mobile.style.height = 0;
        document.body.style.overflow = 'inherit';
      } else {
        this.classList.add('open');
        m._mobile.classList.add('open');
        document.body.style.overflow = 'hidden';
        m._mobile.style.height = window.innerHeight-closest(m._target,'header').clientHeight+'px';
        m._mobile.style.top = m._header.clientHeight +'px';
      }
    })
    this._reset = function(){
      if(m._target.classList.contains('open')){
        if(window.innerWidth > 768) {
          m._target.classList.remove('open');
          m._mobile.classList.remove('open');
          document.body.style.overflow = 'auto';
          m._mobile.style.height = 'auto';
          document.body.style.paddingTop = m._header.clientHeight+'px';
          m._mobile.style.height = 'auto';
        } else {
          m._mobile.style.height = window.innerHeight-closest(m._target,'header').clientHeight+'px';
          m._mobile.style.top = m._header.clientHeight +'px';
        }
      } else {
        if(window.innerWidth < 769) {
          m._mobile.style.height = 0;
        } else {
          m._mobile.style.height = 'auto';
        }
      }
    }
    this._reset();
    window.addEventListener('resize',m._reset,false);
  }
  return Menu;
})();

var Sticky = (function(){
  function Sticky(){
    var s = this;
    this._target = document.getElementById('header');
    this._mobile = document.getElementById('nav');
    this._for_sp = function(top){
      s._target.style.left = 0;
      document.body.style.paddingTop = s._target.clientHeight+'px';
      if(top > 0) {
        s._target.classList.add('fixed');
        document.body.style.paddingTop = s._target.clientHeight+'px';
      } else {
        s._target.classList.remove('fixed');
        document.body.style.paddingTop = 0;
      }
    }
    this._for_pc = function(top,left){
      if(top > 0) {
        s._target.classList.add('fixed');
        document.body.style.paddingTop = '88px';
        s._target.style.left = -left+'px';
      } else {
        s._target.classList.remove('fixed');
        document.body.style.paddingTop = 0;
      }
    }
    this.handling = function(){
      var _top  = document.documentElement.scrollTop || document.body.scrollTop;
      var _left  = document.documentElement.scrollLeft || document.body.scrollLeft;
      if(window.innerWidth < 769) {
        s._for_sp(_top);
      }  else {
        if(!s._target.classList.contains('top')) {
          s._target.classList.remove('fixed')
        }
        s._for_pc(_top,_left);
      }
    }
    window.addEventListener('scroll',s.handling,false);
    window.addEventListener('resize',s.handling,false);
    window.addEventListener('load',s.handling,false);
  }
  return Sticky;
})();
var Anchor = (function() {
  function Anchor() {
    var a = this;
    this._target = '.anchor';
    this._header = document.getElementById('header');
    // this._icon_nav = document.getElementById('nav_ham');
    this._nav = document.getElementById('nav');
    this.timer;
    this.flag_start = false;
    this.iteration;
    this.eles = document.querySelectorAll(this._target);
    this.stopEverything = function() { a.flag_start = false; }
    this._getbuffer = function() {
      var _buffer;
      if(window.innerWidth < 769) {
        _buffer = a._header.clientHeight;
      } else {
        _buffer = a._header.clientHeight;
      }
      return _buffer;
    }

    this.scrollToY = function(scrollTargetY, speed, easing) {
      var scrollY = window.scrollY || window.pageYOffset,
        scrollTargetY = scrollTargetY || 0,
        speed = speed || 2000,
        easing = easing || 'easeOutSine',
        currentTime = 0;
      var time = Math.max(.1, Math.min(Math.abs(scrollY - scrollTargetY) / speed, .8));

      function tick() {
        if (a.flag_start) {
          currentTime += 1 / 60;
          var p = currentTime / time;
          var t = easingEquations[easing](p);
          if (p < 1) {
            requestAnimFrame(tick);
            window.scrollTo(0, scrollY + ((scrollTargetY - scrollY) * t));
          } else { window.scrollTo(0, scrollTargetY); }
        }
      }
      tick();
    }
    Array.prototype.forEach.call(this.eles, function(el, i) {
      el.addEventListener('click', function(e) {
        var next = el.getAttribute('href').split('#')[1];
        if (document.getElementById(next)) {
          a.flag_start = true;
          e.preventDefault();
          a.scrollToY((document.getElementById(next).offsetTop - a._getbuffer() - 0), 1500, 'easeOutSine');
          // if (window.innerWidth < 769) {
          //   a._icon_nav.classList.remove('open');
          //   a._nav.classList.remove('open');
          //   document.body.style.overflow = "inherit";
          // }
          if(window.innerWidth < 768 && closest(this,'#nav')) {
            document.getElementById('icon_nav').click();
          }
        }
      })
    });
    this._start = function() {
      var next = window.location.hash.split('#')[1];
      a.flag_start = true;
      if (next) { a.scrollToY((document.getElementById(next).offsetTop - a._getbuffer() - 0), 1500, 'easeOutSine'); }
    }
    window.addEventListener('load', a._start, false);
    document.querySelector("body").addEventListener('mousewheel', a.stopEverything, false);
    document.querySelector("body").addEventListener('DOMMouseScroll', a.stopEverything, false);
  }
  return Anchor;
})();

var Load = (function(){
  function Load(){
    var l = this;
    this._loading = document.getElementById('loading');
    this.loading = function(){
      setTimeout(function(){
        l._loading.classList.add('end');
      },1000);
    };
    this.loading();
  }
  return Load;
})();
var PageTop = (function(){
  function PageTop(){
    var pa = this;
    this._target = document.getElementById('pagetop');
    this.flag_start = false;
    this.stopEverything = function(){
      pa.flag_start = false;
    }
    this.scrollToY = function(scrollTargetY,speed,easing){
      var scrollY = window.scrollY || window.pageYOffset,
        scrollTargetY = scrollTargetY || 0,
        speed = speed || 2000,
        easing = easing || 'easeOutSine',
        currentTime = 0;
      var time = Math.max(.1, Math.min(Math.abs(scrollY - scrollTargetY) / speed, .8));
      function tick() {
        if(pa.flag_start){
          currentTime += 1 / 60;
          var p = currentTime / time;
          var t = easingEquations[easing](p);
          if (p < 1) {
            requestAnimFrame(tick);
            window.scrollTo(0, scrollY + ((scrollTargetY - scrollY) * t));
          } else {
            window.scrollTo(0, scrollTargetY);
          }
        }
      }
      tick();
    }
    this._target.addEventListener('click',function(e){
      e.preventDefault();
      pa.flag_start = true;
      pa.scrollToY(0,1000,'easeOutSine');
    })
    document.querySelector("body").addEventListener('mousewheel',pa.stopEverything,false);
    document.querySelector("body").addEventListener('DOMMouseScroll',pa.stopEverything,false);
  }
  return PageTop;
})();