$(document).ready(function(){var t=$(".scr-pad-key").closest("table");if(t.hasAttr("target"))var a=$(t.attr("target"));else a=t.find("input");a.focus(),$(".scr-pad-key").disableSelection(),$(".scr-pad-key").click(function(){var t=$(this).text(),a=$(this).closest("table");if(a.hasAttr("target"))var e=$(a.attr("target"));else e=a.find("input");var s=e.val();if($(this).hasClass("btn-del"))""!=s&&(s=s.slice(0,-1),e.val(s),e.focus());else if($(this).hasClass("btn-clear"))""!=s&&e.val("");else if(!$(this).hasClass("btn-enter")){if(e.hasAttr("maxlength")&&!isNaN(e.attr("maxlength")))if(parseInt(e.attr("maxlength"))<s.length+t.length)return e.focus(),!1;e.val(s+t),e.focus()}return!1})});