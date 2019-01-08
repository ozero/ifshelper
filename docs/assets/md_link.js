
/*

from: "Is there an easy way to make all links use target="_blank" so they open in a new tab?"
https://github.com/markdown-it/markdown-it/blob/master/docs/architecture.md#renderer

*/

function md_render_linkblank(){
  /* global md */
  var defaultRender = window.md.renderer.rules.link_open || function(tokens, idx, options, env, self) {
    return self.renderToken(tokens, idx, options);
  };

  window.md.renderer.rules.link_open = function (tokens, idx, options, env, self) {
    // If you are sure other plugins can't add `target` - drop check below
    var aIndex = tokens[idx].attrIndex('target');

    if (aIndex < 0) {
      tokens[idx].attrPush(['target', '_blank']); // add new attribute
    } else {
      tokens[idx].attrs[aIndex][1] = '_blank';    // replace value of existing attr
    }

    // pass token to default renderer.
    return defaultRender(tokens, idx, options, env, self);
  };


}
