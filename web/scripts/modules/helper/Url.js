define(function (require) {
    return {
        base: null,
        setBase: function (value) {
            this.base = value;
        },
        urlFor: function (url) {
            return this.base + url;
        }
    };
});