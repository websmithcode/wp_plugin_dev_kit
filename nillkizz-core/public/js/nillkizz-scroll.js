class Scroll {
  static disable() {
    document.documentElement
      .setAttribute('DisableScroll', '');
  }
  static enable() {
    document.documentElement
      .removeAttribute('DisableScroll');
  }
}