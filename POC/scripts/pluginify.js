(function($){

function F() {}

if (typeof Object.create !== 'function') {
	Object.create = function (o) {
		F.prototype = o;
		return new F();
	};
}

return $.plugin = function(name, object) {
	
	$.fn[name] = function(options) {

		if (!this.length) return this;

		return this.each(function() {
			if (!$.data(this, name)) {
				$.data(this, name, Object.create(object).init(this, options));
			}
		});
	};
	
};

})(jQuery);