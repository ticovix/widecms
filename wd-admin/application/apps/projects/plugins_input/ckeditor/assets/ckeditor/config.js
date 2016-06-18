/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

        CKEDITOR.editorConfig = function (config) {

            config.forcePasteAsPlainText = true;
            config.entities = false;
            config.htmlEncodeOutput = false;
            // %REMOVE_START%
            // The configuration options below are needed when running CKEditor from source files.
            config.plugins = 'dialogui,dialog,dialogadvtab,basicstyles,bidi,blockquote,clipboard,button,panelbutton,panel,floatpanel,colorbutton,colordialog,templates,menu,contextmenu,div,resize,toolbar,elementspath,enterkey,entities,popup,filebrowser,find,floatingspace,listblock,richcombo,font,format,horizontalrule,htmlwriter,fakeobjects,iframe,wysiwygarea,image,indent,indentblock,indentlist,justify,link,list,liststyle,magicline,maximize,newpage,pagebreak,pastetext,pastefromword,preview,print,removeformat,selectall,showblocks,showborders,sourcearea,specialchar,menubutton,scayt,stylescombo,tab,table,tabletools,undo,wsc,lineutils,widget,ckeditor-gwf-plugin,iframedialog,placeholder,youtube,tableresize,wenzgmap,oembed';
            config.skin = 'bootstrapck';
            // %REMOVE_END%
            config.extraPlugins = 'codesnippet';           
            
            config.protectedSource.push(/<\?[\s\S]*?\?>/g); // PHP Code
            //config.protectedSource.push(/<code.*>[\s\S]*?<\/code>/gi); // Code tags

            // Define changes to default configuration here. For example:
            config.language = 'pt-br';
            // config.uiColor = '#AADC6E';
        };
