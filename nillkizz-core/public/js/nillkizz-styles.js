class Styles {
  // Add, remove, or update a stylesheets as string
  static set(css, id) {
    if (id) {
      document.getElementById(id).innerHTML = css;
    } else {
      let style = document.createElement('style');
      style.innerHTML = css;
      document.head.appendChild(style);
    }
  }

  static remove(id) {
    if (id) {
      const $style = document.getElementById(id);
      if ($style) $style.remove();
    }
  }
}