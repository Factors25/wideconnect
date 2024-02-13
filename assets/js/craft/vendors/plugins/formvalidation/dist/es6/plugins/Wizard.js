import t from "../core/Plugin";import e from "../utils/classSet";import s from "./Excluded";export default class i extends t{constructor(t){super(t);this.currentStep=0;this.numSteps=0;this.stepIndexes=[];this.opts=Object.assign({},{activeStepClass:"fv-plugins-wizard--active",onStepActive:()=>{},onStepInvalid:()=>{},onStepValid:()=>{},onValid:()=>{},stepClass:"fv-plugins-wizard--step"},t);this.prevStepHandler=this.onClickPrev.bind(this);this.nextStepHandler=this.onClickNext.bind(this)}install(){this.core.registerPlugin(i.EXCLUDED_PLUGIN,this.opts.isFieldExcluded?new s({excluded:this.opts.isFieldExcluded}):new s);const t=this.core.getFormElement();this.steps=[].slice.call(t.querySelectorAll(this.opts.stepSelector));this.numSteps=this.steps.length;this.steps.forEach((t=>{e(t,{[this.opts.stepClass]:true})}));e(this.steps[0],{[this.opts.activeStepClass]:true});this.stepIndexes=Array(this.numSteps).fill(0).map(((t, e)=>e));this.prevButton=typeof this.opts.prevButton==="string"?this.opts.prevButton.substring(0,1)==="#"?document.getElementById(this.opts.prevButton.substring(1)):t.querySelector(this.opts.prevButton):this.opts.prevButton;this.nextButton=typeof this.opts.nextButton==="string"?this.opts.nextButton.substring(0,1)==="#"?document.getElementById(this.opts.nextButton.substring(1)):t.querySelector(this.opts.nextButton):this.opts.nextButton;this.prevButton.addEventListener("click",this.prevStepHandler);this.nextButton.addEventListener("click",this.nextStepHandler)}uninstall(){this.core.deregisterPlugin(i.EXCLUDED_PLUGIN);this.prevButton.removeEventListener("click",this.prevStepHandler);this.nextButton.removeEventListener("click",this.nextStepHandler);this.stepIndexes.length=0}getCurrentStep(){return this.currentStep}goToPrevStep(){const t=this.currentStep-1;if(t<0){return}const e=this.opts.isStepSkipped?this.stepIndexes.slice(0,this.currentStep).reverse().find(((t, e)=>!this.opts.isStepSkipped({currentStep:this.currentStep,numSteps:this.numSteps,targetStep:t}))):t;this.goToStep(e);this.onStepActive()}goToNextStep(){this.core.validate().then((t=>{if(t==="Valid"){let t=this.currentStep+1;if(t>=this.numSteps){this.currentStep=this.numSteps-1}else{const e=this.opts.isStepSkipped?this.stepIndexes.slice(t,this.numSteps).find(((t, e)=>!this.opts.isStepSkipped({currentStep:this.currentStep,numSteps:this.numSteps,targetStep:t}))):t;t=e;this.goToStep(t)}this.onStepActive();this.onStepValid();if(t===this.numSteps){this.onValid()}}else if(t==="Invalid"){this.onStepInvalid()}}))}goToStep(t){e(this.steps[this.currentStep],{[this.opts.activeStepClass]:false});e(this.steps[t],{[this.opts.activeStepClass]:true});this.currentStep=t}onClickPrev(){this.goToPrevStep()}onClickNext(){this.goToNextStep()}onStepActive(){const t={numSteps:this.numSteps,step:this.currentStep};this.core.emit("plugins.wizard.step.active",t);this.opts.onStepActive(t)}onStepValid(){const t={numSteps:this.numSteps,step:this.currentStep};this.core.emit("plugins.wizard.step.valid",t);this.opts.onStepValid(t)}onStepInvalid(){const t={numSteps:this.numSteps,step:this.currentStep};this.core.emit("plugins.wizard.step.invalid",t);this.opts.onStepInvalid(t)}onValid(){const t={numSteps:this.numSteps};this.core.emit("plugins.wizard.valid",t);this.opts.onValid(t)}}i.EXCLUDED_PLUGIN="___wizardExcluded";