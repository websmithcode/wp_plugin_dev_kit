class NillkizzUtils {
  static disableScroll() {
    document.documentElement
      .setAttribute('DisableScroll', '');
  }
  static enableScroll() {
    document.documentElement
      .removeAttribute('DisableScroll');
  }
  static setCookie(cname, cvalue, exdays) {
    const d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    let expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
  }

  static getCookie(name) {
    const cookies = document.cookie.split(';');
    for (let i = 0; i < cookies.length; i++) {
      let c = cookies[i].trim().split('=');
      if (c[0] === name) {
        return c[1];
      }
    }
    return undefined;
  }
}