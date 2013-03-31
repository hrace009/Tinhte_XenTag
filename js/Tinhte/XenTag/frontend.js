!function($,window,document,_undefined){XenForo.Tinhte_XenTag_TagsEditor=function($ul){this.__construct($ul);};XenForo.Tinhte_XenTag_TagsEditor.prototype={__construct:function($ul){this.$ul=$ul;this.$input=$ul.find('.Tinhte_XenTag_TagNewInput');this.varName=$ul.data('varname');this.regex=/[,،]+/gi;this.separator=/[,،]/;$ul.find('li').each(function(i){var $this=$(this);if($this.find('input.textCtrl').length>0){$this.addClass('Tinhte_XenTag_TagNew');}else{$this.addClass('Tinhte_XenTag_OtherControls');}});$ul.addClass('textCtrl');$ul.click($.context(this,'ulClick'));this.$input.keyup($.context(this,'inputKeystroke'));var tags=this.$input.val().split(this.separator);for(var i in tags){var tag=this.validateInput(tags[i]);this.createTag(tag);}
this.$input.val('');},ulClick:function(e){var $target=$(e.target);if($target.hasClass('delete')){$target.parents('.Tinhte_XenTag_Tag').remove();}else{this.$input.focus();}},inputKeystroke:function(e){var code=e.which;switch(code){case 13:case 44:e.preventDefault();var value=this.validateInput(this.$input.val());this.createTag(value);this.$input.val('');break;default:var text=this.$input.val();if(text.match(this.separator)!==null){e.preventDefault();var parts=text.split(this.separator);for(var i=0;i<parts.length;i++){parts[i]=this.validateInput(parts[i]);this.createTag(parts[i]);}
this.$input.val('');}}},validateInput:function(value){value=value.replace(this.regex,'');value=$.trim(value);return value;},isNew:function(value){var isNew=true;this.$ul.find('.Tinhte_XenTag_Tag').each(function(i){var tagValue=$(this).find('input').val();if(value==tagValue){isNew=false;}});return isNew;},createTag:function(value){if(value!=''&&this.isNew(value)){var $li=$('<li />');var $input=$('<input />');$input.attr('type','hidden').attr('name',this.varName).attr('value',value);$li.addClass('Tinhte_XenTag_Tag').text(value).append('<a class="delete">x</a>').append($input);$li.insertBefore(this.$input.parents('.Tinhte_XenTag_TagNew'));}}};XenForo.Tinhte_XenTag_TagsInlineEditor=function($element){this.__construct($element);};XenForo.Tinhte_XenTag_TagsInlineEditor.prototype={__construct:function($element){this.$element=$element;this.$trigger=$element.find('.Tinhte_XenTag_Trigger');this.$trigger.click($.context(this,'triggerClick'));},triggerClick:function(e){e.preventDefault();XenForo.ajax(this.$trigger.attr('href'),{_Tinhte_XenTag_TagsInlineEditor:1},$.context(this,'ajaxSuccessForTrigger'));},ajaxSuccessForTrigger:function(ajaxData){if(XenForo.hasResponseError(ajaxData)||!XenForo.hasTemplateHtml(ajaxData)){return false;}
var $element=this.$element;var $templateHtml=$(ajaxData.templateHtml);var $saveClick=$.context(this,'saveClick');var $cancelClick=$.context(this,'cancelClick');this.$form=$templateHtml;new XenForo.ExtLoader(ajaxData,function(){$templateHtml.addClass('Tinhte_XenTag_TagsInlineEditorForm');$templateHtml.append('<input type="hidden" name="_Tinhte_XenTag_callerTemplate" value="'+$element.data('template')+'" />');$templateHtml.xfInsert('insertAfter',$element,'show');$templateHtml.find('.button.primary').click($saveClick);$templateHtml.find('.button.cancel').click($cancelClick);$templateHtml.find('input[type=text]').focus();$element.hide();});},saveClick:function(e){e.preventDefault();var serialized=this.$form.serializeArray();var action=this.$form.attr('action');XenForo.ajax(action,serialized,$.context(this,'ajaxSuccessForSave'));},cancelClick:function(e){e.preventDefault();this.$element.show();this.$form.empty().xfRemove();},ajaxSuccessForSave:function(ajaxData){if(XenForo.hasResponseError(ajaxData)||!XenForo.hasTemplateHtml(ajaxData)){return false;}
var $element=this.$element;var $form=this.$form;var $templateHtml=$('<div />').append($(ajaxData.templateHtml).find('.Tinhte_XenTag_TagsInlineEditor'));new XenForo.ExtLoader(ajaxData,function(){$templateHtml.xfInsert('insertAfter',$element,'show');$element.empty().xfRemove();$form.empty().xfRemove();});}};XenForo.Tinhte_XenTag_TagCloud=function($element){this.__construct($element);};XenForo.Tinhte_XenTag_TagCloud.prototype={__construct:function($element){this.$element=$element;var sortToggler=$element.data('toggler');if(sortToggler){var $sortToggler=$('<a href="#"/>').text($element.data('toggler')).toggle(function(e){$element.find('li').tsort({order:'desc',attr:'data-level'});e.preventDefault();},function(e){$element.find('li').tsort({order:'asc',attr:'data-text'});e.preventDefault();});$sortToggler.xfInsert('insertAfter',$element);}}};XenForo.register('ul.Tinhte_XenTag_TagsEditor','XenForo.Tinhte_XenTag_TagsEditor');XenForo.register('.Tinhte_XenTag_TagsInlineEditor','XenForo.Tinhte_XenTag_TagsInlineEditor');XenForo.register('.Tinhte_XenTag_TagCloud','XenForo.Tinhte_XenTag_TagCloud');}
(jQuery,this,document);