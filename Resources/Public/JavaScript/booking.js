
jQuery.fn.center = function () {
  this.css("position","absolute");
  this.css("top", (($(window).height() - this.outerHeight()) / 2) - 200 + $(window).scrollTop() + "px");
  this.css("left", (($(window).width() - this.outerWidth()) / 2) + $(window).scrollLeft() + "px");

  this.fadeOut(4000);
  return this;
}


// call
// $('.error').center();