"use strict";function reCAPTCHA(t,a){$("#hide-all").show();var e=document.getElementsByTagName("head")[0],n=document.createElement("script");n.type="text/javascript",n.id="re-captcha-script",n.src="https://www.google.com/recaptcha/api.js?render="+$("#re-captcha-key").val(),e.appendChild(n),$("#re-captcha-script").on("load",function(){grecaptcha.ready(function(){grecaptcha.execute($("#re-captcha-key").val(),{action:"validateReCAPTCHA"},!1).then(function(e){$("#re-captcha-token").val(e),"contactPage"===a?t.submit():"modelPage"===a&&$.post(t.attr("action"),t.serialize(),function(e){"true"===e.trim()&&(sessionStorage.isThankYou="true"),location.reload()})})})})}$(document).ready(function(){var t=$("#nav-select-make"),a=$("#nav-select-model");function n(){""===a.val()?window.location.href="/make/"+encodeURIComponent(t.val()):window.location.href="/model/"+encodeURIComponent(t.val())+"/"+encodeURIComponent(a.val())}t.on("change",function(){var e;e=t.val(),a.empty(),a.append('<option value="">Model</option>'),""!==e&&$.get("/api/getModelNames/"+e,null,function(e){$.each(e,function(e,t){a.append('<option value="'+t+'">'+t+"</option>")})})}),a.on("change",function(){n()}),$("#search-form-submit").on("click",function(e){""===$("#search-form-text").val()&&(e.preventDefault(),""!==t.val()&&n())})});