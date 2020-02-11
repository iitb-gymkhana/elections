function rgb2hex(rgb) {
    if (rgb.startsWith('#')) return rgb;

    rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
    function hex(x) {
        return ("0" + parseInt(x).toString(16)).slice(-2);
    }
    return "#" + hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3]);
}

function getColor(el, key) {
    return rgb2hex(getComputedStyle(document.getElementById(el)).getPropertyValue(key));
}

var opts = {
    width: '100%',
    background: getColor('top', 'background-color'),
    foreground: getColor('top', 'color'),
    cornerRadius: '25px',
    padding: '12px 25px',
};

buildSSOWidget(sso, 'ssod', redirect, opts);

