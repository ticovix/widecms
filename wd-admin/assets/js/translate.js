$(function(){
  function translateTo(language){
    console.log(Microsoft.Translator.Widget);
    Microsoft.Translator.Widget.Translate('pt', language, onProgress, null, onComplete, null, null);
    Microsoft.Translator.Widget.domTranslator.showTooltips=false;
    Microsoft.Translator.Widget.domTranslator.showHighlight=false;
  }

  function onProgress(value) {
      //$(".loading-translate").fadeIn(500);
  }

  function onComplete(value) {
      //$(".loading-translate").fadeOut(500);
  }

  if($language!='' && $language!='pt'){
      translateTo($language);
  }

});