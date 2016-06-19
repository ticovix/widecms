$(function(){
        hljs.initHighlightingOnLoad();
        /*hljs.configure({useBR: true});*/
        $('pre code').each(function(i, block) {
            hljs.highlightBlock(block);
          });
    });