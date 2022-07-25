class RGB {
  constructor(rgb = [0, 0, 0]) {
    this.r = rgb[0];
    this.g = rgb[1];
    this.b = rgb[2];
  }
  static from_hex(hex) {
    if (hex.match(/[\w\d]/g).length == 3) {
      var aRgbHex = hex.match(/[\w\d]/g);
      var aRgb = [
        parseInt(aRgbHex[0], 16) * 17,
        parseInt(aRgbHex[1], 16) * 17,
        parseInt(aRgbHex[2], 16) * 17
      ];
    } else {
      var aRgbHex = hex.match(/[\w\d]{2}/g);
      var aRgb = [
        parseInt(aRgbHex[0], 16),
        parseInt(aRgbHex[1], 16),
        parseInt(aRgbHex[2], 16)
      ];
    }
    return new RGB(aRgb);
  }

  toHex() {
    const convertColor = (c) => c.toString(16).padStart(2, '0')
    return `#${convertColor(this.r)}${convertColor(this.g)}${convertColor(this.b)}`
  }

  dim(v) {
    const calc = c => Math.max(0, c - v)
    this.r = calc(this.r);
    this.g = calc(this.g);
    this.b = calc(this.b);
    return this;
  }
  bright(v) {
    const calc = c => Math.min(255, c + v)
    this.r = calc(this.r);
    this.g = calc(this.g);
    this.b = calc(this.b);
    return this;
  }

  toCssRGB() {
    return `rgb(${this.r}, ${this.g}, ${this.b})`
  }
}