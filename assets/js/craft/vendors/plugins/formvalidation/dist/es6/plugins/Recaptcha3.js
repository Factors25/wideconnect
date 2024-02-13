import e from "../core/Plugin";import t from "../utils/fetch";export default class s extends e{constructor(e){super(e);this.opts=Object.assign({},{minimumScore:0},e);this.iconPlacedHandler=this.onIconPlaced.bind(this)}install(){this.core.on("plugins.icon.placed",this.iconPlacedHandler);const e=typeof window[s.LOADED_CALLBACK]==="undefined"?()=>{}:window[s.LOADED_CALLBACK];window[s.LOADED_CALLBACK]=()=>{e();const i=document.createElement("input");i.setAttribute("type","hidden");i.setAttribute("name",s.CAPTCHA_FIELD);document.getElementById(this.opts.element).appendChild(i);this.core.addField(s.CAPTCHA_FIELD,{validators:{promise:{message:this.opts.message,promise: e=>new Promise(((e, i)=>{window["grecaptcha"].execute(this.opts.siteKey,{action:this.opts.action}).then((o=>{t(this.opts.backendVerificationUrl,{method:"POST",params:{[s.CAPTCHA_FIELD]:o}}).then((t=>{const s=`${t.success}`==="true"&&t.score>=this.opts.minimumScore;e({message:t.message||this.opts.message,meta:t,valid:s})})).catch((e=>{i({valid:false})}))}))}))}}})};const i=this.getScript();if(!document.body.querySelector(`script[src="${i}"]`)){const e=document.createElement("script");e.type="text/javascript";e.async=true;e.defer=true;e.src=i;document.body.appendChild(e)}}uninstall(){this.core.off("plugins.icon.placed",this.iconPlacedHandler);const e=this.getScript();const t=[].slice.call(document.body.querySelectorAll(`script[src="${e}"]`));t.forEach((e=>e.parentNode.removeChild(e)));this.core.removeField(s.CAPTCHA_FIELD)}getScript(){const e=this.opts.language?`&hl=${this.opts.language}`:"";return"https://www.google.com/recaptcha/api.js?"+`onload=${s.LOADED_CALLBACK}&render=${this.opts.siteKey}${e}`}onIconPlaced(e){if(e.field===s.CAPTCHA_FIELD){e.iconElement.style.display="none"}}}s.CAPTCHA_FIELD="___g-recaptcha-token___";s.LOADED_CALLBACK="___reCaptcha3Loaded___";