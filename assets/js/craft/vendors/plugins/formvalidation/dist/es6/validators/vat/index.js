import r from "../../utils/format";import a from "./arVat";import e from "./atVat";import t from "./beVat";import o from "./bgVat";import m from "./brVat";import s from "./chVat";import i from "./cyVat";import c from "./czVat";import f from "./deVat";import b from "./dkVat";import p from "./eeVat";import k from "./esVat";import V from "./fiVat";import n from "./frVat";import l from "./gbVat";import u from "./grVat";import v from "./hrVat";import g from "./huVat";import d from "./ieVat";import E from "./isVat";import h from "./itVat";import R from "./ltVat";import y from "./luVat";import L from "./lvVat";import C from "./mtVat";import S from "./nlVat";import I from "./noVat";import O from "./plVat";import T from "./ptVat";import U from "./roVat";import z from "./rsVat";import B from "./ruVat";import A from "./seVat";import G from "./siVat";import H from "./skVat";import j from "./veVat";import w from "./zaVat";export default function x(){const x=["AR","AT","BE","BG","BR","CH","CY","CZ","DE","DK","EE","EL","ES","FI","FR","GB","GR","HR","HU","IE","IS","IT","LT","LU","LV","MT","NL","NO","PL","PT","RO","RU","RS","SE","SK","SI","VE","ZA"];return{validate(D){const F=D.value;if(F===""){return{valid:true}}const K=Object.assign({},{message:""},D.options);let N=F.substr(0,2);if("function"===typeof K.country){N=K.country.call(this)}else{N=K.country}if(x.indexOf(N)===-1){return{valid:true}}let P={meta:{},valid:true};switch(N.toLowerCase()){case"ar":P=a(F);break;case"at":P=e(F);break;case"be":P=t(F);break;case"bg":P=o(F);break;case"br":P=m(F);break;case"ch":P=s(F);break;case"cy":P=i(F);break;case"cz":P=c(F);break;case"de":P=f(F);break;case"dk":P=b(F);break;case"ee":P=p(F);break;case"el":P=u(F);break;case"es":P=k(F);break;case"fi":P=V(F);break;case"fr":P=n(F);break;case"gb":P=l(F);break;case"gr":P=u(F);break;case"hr":P=v(F);break;case"hu":P=g(F);break;case"ie":P=d(F);break;case"is":P=E(F);break;case"it":P=h(F);break;case"lt":P=R(F);break;case"lu":P=y(F);break;case"lv":P=L(F);break;case"mt":P=C(F);break;case"nl":P=S(F);break;case"no":P=I(F);break;case"pl":P=O(F);break;case"pt":P=T(F);break;case"ro":P=U(F);break;case"rs":P=z(F);break;case"ru":P=B(F);break;case"se":P=A(F);break;case"si":P=G(F);break;case"sk":P=H(F);break;case"ve":P=j(F);break;case"za":P=w(F);break;default:break}const Z=r(D.l10n&&D.l10n.vat?K.message||D.l10n.vat.country:K.message,D.l10n&&D.l10n.vat&&D.l10n.vat.countries?D.l10n.vat.countries[N.toUpperCase()]:N.toUpperCase());return Object.assign({},{message:Z},P)}}}