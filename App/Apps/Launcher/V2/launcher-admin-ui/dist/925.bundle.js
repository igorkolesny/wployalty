"use strict";(self.webpackChunkreact_wordpress=self.webpackChunkreact_wordpress||[]).push([[925],{5663:(e,t,n)=>{n.d(t,{Z:()=>l});var r=n(7294),o=n(3972);const l=function(e){var t=e.description,n=e.handleRemoveImage,l=e.handleChooseImage,a=e.value,c=r.useContext(o.Gr);return r.createElement("div",{className:"flex flex-col w-full gap-y-2 2xl:gap-y-3"},t&&r.createElement("p",{className:"text-light 2xl:text-sm text-xs font-normal  "},t),r.createElement("div",{className:"flex items-center justify-center w-full h-50 border-2 border-light_border border-dashed rounded-md"},["",void 0].includes(a)?r.createElement("div",{className:"flex flex-col items-center gap-y-3"},r.createElement("i",{className:"wlr wlrf-image-upload text-light text-3xl"}),r.createElement("p",{className:"text-xs 2xl:text-sm text-light font-medium"},c.common.image_description)):r.createElement("img",{src:a,alt:"logo image",className:"object-contain h-full w-full p-1 rounded-md "})),r.createElement("div",{className:"flex w-full gap-4 items-center gap-x-3"},r.createElement("div",{onClick:n,className:"flex items-center cursor-pointer justify-center w-full rounded-md py-3 border border-light_border "},r.createElement("p",{className:"text-light 2xl:text-sm text-xs"},c.common.restore_default)),r.createElement("div",{onClick:l,className:"flex items-center cursor-pointer justify-center w-full rounded-md py-3 bg-blue_primary "},r.createElement("p",{className:"text-white 2xl:text-sm text-xs"},c.common.browse_image))))}},1534:(e,t,n)=>{n.d(t,{Z:()=>o});var r=n(7294);const o=function(e){var t=e.placeHolder,n=void 0===t?null:t,o=e.type,l=void 0===o?"text":o,a=e.value,c=e.required,s=e.onChange,i=e.textColor,m=void 0===i?"text-dark":i,u=e.border,d=e.others,f=void 0===d?"":d,x=e.onfocus,p=void 0===x?null:x,b=e.onblur,g=void 0===b?null:b,v=e.min,w=void 0===v?null:v,h=e.max,E=void 0===h?null:h,_=e.error,y=void 0!==_&&_,O=e.id,N=e.padding,j=void 0===N?"2xl:p-2.5 p-1.5":N,k=e.onKeyDown,Z=e.height,C=void 0===Z?"".concat(["text","number"].includes(l)?"h-11":"h-20"):Z;return"textarea"===l?r.createElement("textarea",{id:O,value:a,required:c,placeholder:n,className:"".concat(j,"  transition duration-200 ease-in focus:outline-none rounded focus:shadow-none antialiased bg-white ").concat(u," 2xl:focus:border-2  w-full  ").concat(m," ").concat(f," ").concat(y&&"wll_input-error"),onChange:s,onFocus:p}):r.createElement("input",{id:O,type:l,value:a,required:c,min:"number"==l?0:w,max:E,placeholder:n,className:"".concat(j," ").concat(C," transition duration-200 ease-in focus:outline-none rounded focus:shadow-none antialiased bg-white ").concat(u," \n      2xl:focus:border-2 w-full ").concat(m,"  tracking-wider ").concat(f," ").concat(y&&"wll_input-error"," "),onChange:s,onFocus:p,onBlur:g,onKeyDown:k})}},7692:(e,t,n)=>{n.d(t,{Z:()=>l});var r=n(7294),o=n(1534);const l=function(e){var t=e.label,n=e.width,l=void 0===n?"w-full":n,a=e.type,c=void 0===a?"text":a,s=e.onChange,i=e.value,m=e.border,u=void 0===m?"border border-card_border  focus:border-blue_primary  focus:border-1  focus:border-opacity-100":m,d=e.error_message,f=e.error;return r.createElement("div",{className:"flex flex-col gap-y-1 ".concat(l)},r.createElement("p",{className:"text-light text-xs 2xl:text-sm font-semibold tracking-wider"},t),r.createElement(o.Z,{type:c,value:i,onChange:s,border:u,error:f}),d&&r.createElement("div",{className:"flex items-center space-x-1"},r.createElement("i",{className:"text-md  antialiased wlr wlrf-error font-semibold text-redd color-important "}),r.createElement("p",{className:"text-redd font-semibold text-xs  tracking-wide"},d)))}},4925:(e,t,n)=>{n.r(t),n.d(t,{default:()=>Q});var r=n(4572),o=n(5861),l=n(8152),a=n(4687),c=n.n(a),s=n(7294),i=n(9735),m=n(2421),u=n(5084),d=n(6693),f=n(7692),x=n(3972),p=n(1393),b=n(5458),g=n(2563);const v=function(e){var t=e.label,n=e.value;return s.createElement("div",{className:"w-full flex flex-col  p-1.5  gap-y-1"},s.createElement("p",{className:"  text-dark   2xl:text-md text-sm font-medium"},n),s.createElement("p",{className:" text-light   text-xs font-normal"},t))};function w(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(e);t&&(r=r.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,r)}return n}const h=function(e){var t=e.setActiveSidebar,n=s.useContext(x.Gr),o=s.useState(!1),a=(0,l.Z)(o,2),c=a[0],i=a[1],m=s.useContext(x.BE),h=m.errors,E=m.errorList,_=s.useContext(x.ko),y=_.commonState,O=_.setCommonState,N=y.content.guest.welcome,j=function(e,t,n){var o=function(e){for(var t=1;t<arguments.length;t++){var n=null!=arguments[t]?arguments[t]:{};t%2?w(Object(n),!0).forEach((function(t){(0,r.Z)(e,t,n[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(n)):w(Object(n)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(n,t))}))}return e}({},y);o.content.guest.welcome[t][n]=e.target.value,O(o)};return s.createElement("div",{className:"h-full w-full flex flex-col ",style:{transition:"all 0.5s ease-in-out"}},s.createElement(u.Z,{title:n.common.welcome,click:function(){return t("guest")}}),s.createElement("div",{className:"flex flex-col w-full h-[488px] overflow-y-auto "},s.createElement(d.Z,{title:n.common.texts},s.createElement(f.Z,{label:n.common.title,value:N.texts.title,error:E.includes("content.guest.welcome.texts.title"),error_message:E.includes("content.guest.welcome.texts.title")&&(0,p.e$)(h,"content.guest.welcome.texts.title"),onChange:function(e){return j(e,"texts","title")}}),s.createElement(f.Z,{label:n.common.description,value:N.texts.description,error:E.includes("content.guest.welcome.texts.description"),error_message:E.includes("content.guest.welcome.texts.description")&&(0,p.e$)(h,"content.guest.welcome.texts.description"),onChange:function(e){return j(e,"texts","description")},type:"textarea"}),s.createElement(f.Z,{label:n.guest.welcome.texts.have_account,value:N.texts.have_account,error:E.includes("content.guest.welcome.texts.have_account"),error_message:E.includes("content.guest.welcome.texts.have_account")&&(0,p.e$)(h,"content.guest.welcome.texts.have_account"),onChange:function(e){return j(e,"texts","have_account")}}),s.createElement(f.Z,{label:n.guest.welcome.texts.sign_in,value:N.texts.sign_in,error:E.includes("content.guest.welcome.texts.sign_in"),error_message:E.includes("content.guest.welcome.texts.sign_in")&&(0,p.e$)(h,"content.guest.welcome.texts.sign_in"),onChange:function(e){return j(e,"texts","sign_in")}}),s.createElement(f.Z,{label:n.common.link,value:N.texts.sign_in_url,error:E.includes("content.guest.welcome.texts.sign_in_url"),error_message:E.includes("content.guest.welcome.texts.sign_in_url")&&(0,p.e$)(h,"content.guest.welcome.texts.sign_in_url"),onChange:function(e){return j(e,"texts","sign_in_url")}})),s.createElement(d.Z,{title:n.common.buttons},s.createElement(f.Z,{label:n.guest.welcome.buttons.create_account,value:N.button.text,error:E.includes("content.guest.welcome.button.text"),error_message:E.includes("content.guest.welcome.button.text")&&(0,p.e$)(h,"content.guest.welcome.button.text"),onChange:function(e){return j(e,"button","text")}}),s.createElement(f.Z,{label:n.common.link,value:N.button.url,error:E.includes("content.guest.welcome.button.url"),error_message:E.includes("content.guest.welcome.button.url")&&(0,p.e$)(h,"content.guest.welcome.button.url"),onChange:function(e){return j(e,"button","url")}})),s.createElement(d.Z,null,s.createElement("div",{className:"flex flex-col w-full ".concat(c?"h-[200px]":"h-10"," transition-all  ease-out overflow-hidden  bg-grey_extra_light border border-card_border rounded-md ")},s.createElement("div",{className:"w-full flex items-center cursor-pointer justify-between w-full p-1.5",onClick:function(){return i(!c)}},s.createElement("div",{className:"flex items-center p-1 gap-x-2"},s.createElement(g.Z,{icon:"info_circle",color:"text-dark"}),s.createElement("p",{className:"text-dark font-medium 2xl:text-md text-sm "},n.member.banner.shortcodes)),s.createElement(g.Z,{icon:"arrow-down",color:"text-dark"})),s.createElement("span",{className:"border-b border-light_border w-full"}),s.createElement("div",{className:"flex flex-col w-full h-full overflow-y-auto "},n.shortcodes.content.guest.welcome.shortcodes.map((function(e,t){return s.createElement(v,{key:t,label:e.label,value:e.value})}))))),s.createElement(b.Z,{click:function(){return t("guest")}})))};var E=n(516),_=n(5663);function y(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(e);t&&(r=r.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,r)}return n}function O(e){for(var t=1;t<arguments.length;t++){var n=null!=arguments[t]?arguments[t]:{};t%2?y(Object(n),!0).forEach((function(t){(0,r.Z)(e,t,n[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(n)):y(Object(n)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(n,t))}))}return e}const N=function(e){var t=e.setActiveSidebar,n=s.useContext(x.Gr),r=s.useContext(x.ko),o=r.commonState,l=r.setCommonState,a=o.content,c=s.useContext(x.BE),i=c.errors,m=c.errorList,g=a.guest.points,v=function(e){var t=O({},o);t.content.guest.points[e].icon.image="",l(t)},w=function(e){var t=wp.media({title:"Select media",multiple:!1,library:{type:"image"}});return t.on("select",(function(){t.state().get("selection").each((function(t){var n=t.attributes.url,r=O({},o);r.content.guest.points[e].icon.image=n,l(r)}))})),t.open(),!1},h=function(e,t,n){var r=O({},o);r.content.guest.points[t][n]=e.target.value,l(r)};return s.createElement("div",null,s.createElement(u.Z,{title:n.common.points,click:function(){return t("guest")}}),s.createElement("div",{className:"flex flex-col w-full h-[488px] overflow-y-auto "},s.createElement(d.Z,{title:n.common.earn},s.createElement(f.Z,{label:n.common.title,value:g.earn.title,error:m.includes("content.guest.points.earn.title"),error_message:m.includes("content.guest.points.earn.title")&&(0,p.e$)(i,"content.guest.points.earn.title"),onChange:function(e){return h(e,"earn","title")}}),s.createElement("p",{className:"text-light text-xs 2xl:text-sm font-semibold tracking-wider"},"Icon"),s.createElement(_.Z,{value:g.earn.icon.image,handleChooseImage:function(){return w("earn")},handleRemoveImage:function(){return v("earn")}})),s.createElement(d.Z,{title:n.common.redeem},s.createElement(f.Z,{label:n.common.title,value:g.redeem.title,error:m.includes("content.guest.points.redeem.title"),error_message:m.includes("content.guest.points.redeem.title")&&(0,p.e$)(i,"content.guest.points.redeem.title"),onChange:function(e){return h(e,"redeem","title")}}),s.createElement("p",{className:"text-light text-xs 2xl:text-sm font-semibold tracking-wider"},n.common.icon),s.createElement(_.Z,{value:g.redeem.icon.image,handleChooseImage:function(){return w("redeem")},handleRemoveImage:function(){return v("redeem")}})),s.createElement(b.Z,{click:function(){return t("guest")}})))};function j(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(e);t&&(r=r.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,r)}return n}const k=function(e){var t=e.setActiveSidebar,n=s.useContext(x.Gr),o=s.useContext(x.ko),l=o.commonState,a=o.setCommonState,c=s.useContext(x.BE),i=c.errors,m=c.errorList,g=l.content.guest.referrals,v=function(e,t){var n=function(e){for(var t=1;t<arguments.length;t++){var n=null!=arguments[t]?arguments[t]:{};t%2?j(Object(n),!0).forEach((function(t){(0,r.Z)(e,t,n[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(n)):j(Object(n)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(n,t))}))}return e}({},l);n.content.guest.referrals[t]=e.target.value,a(n)};return s.createElement("div",null,s.createElement(u.Z,{title:n.common.referrals,click:function(){return t("guest")}}),s.createElement("div",{className:"flex flex-col w-full h-[520px] overflow-y-auto "},s.createElement(d.Z,{title:n.common.texts},s.createElement(f.Z,{label:n.common.title,value:g.title,error:m.includes("content.guest.referrals.title"),error_message:m.includes("content.guest.referrals.title")&&(0,p.e$)(i,"content.guest.referrals.title"),onChange:function(e){return v(e,"title")}}),s.createElement(f.Z,{label:n.common.description,value:g.description,error:m.includes("content.guest.referrals.description"),error_message:m.includes("content.guest.referrals.description")&&(0,p.e$)(i,"content.guest.referrals.description"),onChange:function(e){return v(e,"description")},type:"textarea"})),s.createElement(b.Z,{click:function(){return t("guest")}})))};function Z(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(e);t&&(r=r.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,r)}return n}const C=function(e){var t=e.setActiveSidebar,n=s.useContext(x.Gr),o=s.useContext(x.ko),a=o.commonState,c=o.setCommonState,i=(o.appState,s.useContext(x.BE)),m=i.errors,w=i.errorList,h=s.useState(!1),E=(0,l.Z)(h,2),_=E[0],y=E[1],O=a.content.member.referrals,N=function(e,t){var n=function(e){for(var t=1;t<arguments.length;t++){var n=null!=arguments[t]?arguments[t]:{};t%2?Z(Object(n),!0).forEach((function(t){(0,r.Z)(e,t,n[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(n)):Z(Object(n)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(n,t))}))}return e}({},a);n.content.member.referrals[t]=e.target.value,c(n)};return s.createElement("div",null,s.createElement(u.Z,{title:n.common.referrals,click:function(){return t("member")}}),s.createElement("div",{className:"flex flex-col w-full h-[475px] overflow-y-auto "},s.createElement(d.Z,{title:n.common.texts},s.createElement(f.Z,{label:n.common.title,value:O.title,error:w.includes("content.member.referrals.title"),error_message:w.includes("content.member.referrals.title")&&(0,p.e$)(m,"content.member.referrals.title"),onChange:function(e){return N(e,"title")}}),s.createElement(f.Z,{label:n.common.description,value:O.description,error:w.includes("content.member.referrals.description"),error_message:w.includes("content.member.referrals.description")&&(0,p.e$)(m,"content.member.referrals.description"),onChange:function(e){return N(e,"description")},type:"textarea"}),s.createElement("div",{className:"flex flex-col w-full ".concat(_?"h-[252px]":"h-10"," transition-all  ease-out overflow-hidden  \n                    px-2\n                    bg-grey_extra_light border border-card_border rounded-md ")},s.createElement("div",{className:"w-full flex items-center cursor-pointer justify-between  w-full p-1.5",onClick:function(){return y(!_)}},s.createElement("div",{className:"flex items-center p-1 gap-x-2"},s.createElement(g.Z,{icon:"info_circle",color:"text-dark"}),s.createElement("p",{className:"text-dark font-medium 2xl:text-md text-sm "},n.member.banner.shortcodes)),s.createElement(g.Z,{icon:"arrow-down",color:"text-dark"})),s.createElement("span",{className:"border-b border-light_border w-full"}),s.createElement("div",{className:"flex flex-col w-full h-full overflow-y-auto "},n.shortcodes.content.member.referrals.shortcodes.map((function(e,t){return s.createElement(v,{key:t,label:e.label,value:e.value})}))))),s.createElement(b.Z,{click:function(){return t("member")}})))},S=function(e){var t=e.isActive,n=e.click,r=e.deactivate_tooltip,o=void 0===r?"click to de-activate":r,l=e.activate_tooltip,a=void 0===l?"click to activate":l,c=e.isPro,i=void 0===c||c;return s.createElement("div",{className:"flex items-center  p-0.5 2xl:w-11 2xl:h-6 w-9 h-5 \n    ".concat(i?"cursor-pointer":"cursor-not-allowed"," transition delay-150 ease rounded-xl\n    ").concat(t&&i?"bg-blue_primary justify-end ":"bg-light_gray justify-start","\n  \n    "),title:t?o:a,onClick:n},s.createElement("span",{className:" 2xl:h-5 h-4 2xl:w-5 w-4 rounded-full\n         bg-white\n         "}))};function P(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(e);t&&(r=r.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,r)}return n}function D(e){for(var t=1;t<arguments.length;t++){var n=null!=arguments[t]?arguments[t]:{};t%2?P(Object(n),!0).forEach((function(t){(0,r.Z)(e,t,n[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(n)):P(Object(n)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(n,t))}))}return e}const I=function(e){var t=e.setActiveSidebar,n=s.useContext(x.Gr),r=s.useContext(x.ko),o=r.commonState,a=r.appState,c=r.setCommonState,i=s.useContext(x.BE),m=i.errors,w=i.errorList,h=s.useState(!1),E=(0,l.Z)(h,2),_=E[0],y=E[1],O=o.content.member.banner,N=function(e,t,n){var r=D({},o);r.content.member.banner[t][n]=e.target.value,c(r)};return s.createElement("div",null,s.createElement(u.Z,{title:n.common.banner,click:function(){return t("member")}}),s.createElement("div",{className:"flex flex-col w-full h-[488px] overflow-y-auto "},s.createElement(d.Z,{title:n.member.banner.levels},s.createElement("div",{className:"flex items-center justify-between w-full"},s.createElement("p",{className:"text-dark font-normal 2xl:text-sm text-xs tracking-wide"},"show"===O.levels.is_show?n.common.enabled:n.common.disabled),s.createElement("div",{className:"flex items-center gap-x-2"},!a.is_pro&&s.createElement("div",{className:"flex items-center  cursor-pointer   justify-center "},s.createElement("span",{className:"bg-blue_primary text-white font-medium rounded text-xs px-1.5 py-1",onClick:function(e){e.preventDefault(),window.open(n.common.buy_pro_url)}},n.common.upgrade_text)),s.createElement(S,{isActive:"show"===O.levels.is_show,click:a.is_pro?function(e){e.preventDefault();var t=D({},o);"show"===O.levels.is_show?(t.content.member.banner.levels.is_show="none",c(t)):(t.content.member.banner.levels.is_show="show",c(t))}:function(){},isPro:a.is_pro,activate_tooltip:n.common.toggle.activate,deactivate_tooltip:n.common.toggle.deactivate})))),s.createElement(d.Z,{title:n.common.text},s.createElement(f.Z,{label:n.common.texts,value:O.texts.welcome,error:w.includes("content.member.banner.texts.welcome"),error_message:w.includes("content.member.banner.texts.welcome")&&(0,p.e$)(m,"content.member.banner.texts.welcome"),onChange:function(e){return N(e,"texts","welcome")}}),s.createElement("div",{className:"flex items-center justify-between w-full"},s.createElement("p",{className:"text-light  text-xs 2xl:text-sm font-semibold tracking-wider"},n.member.banner.points),s.createElement("div",{className:"flex items-center gap-x-2"},s.createElement("p",{className:"text-dark font-normal  text-xs tracking-wide"},"show"===O.points.is_show?n.common.enabled:n.common.disabled),s.createElement(S,{isActive:"show"===O.points.is_show,click:function(e){e.preventDefault();var t=D({},o);"show"===O.points.is_show?(t.content.member.banner.points.is_show="none",c(t)):(t.content.member.banner.points.is_show="show",c(t))},activate_tooltip:n.common.toggle.activate,deactivate_tooltip:n.common.toggle.deactivate}))),s.createElement(f.Z,{label:n.member.banner.point_description,value:O.texts.points_label,error:w.includes("content.member.banner.texts.points_label"),error_message:w.includes("content.member.banner.texts.points_label")&&(0,p.e$)(m,"content.member.banner.texts.points_label"),onChange:function(e){return N(e,"texts","points_label")}})),s.createElement(d.Z,null,s.createElement("div",{className:"flex flex-col w-full ".concat(_?"h-[252px]":"h-10"," transition-all  ease-out overflow-hidden  bg-grey_extra_light border border-card_border rounded-md ")},s.createElement("div",{className:"w-full flex items-center cursor-pointer justify-between w-full p-1.5",onClick:function(){return y(!_)}},s.createElement("div",{className:"flex items-center p-1 gap-x-2"},s.createElement(g.Z,{icon:"info_circle",color:"text-dark"}),s.createElement("p",{className:"text-dark font-medium 2xl:text-md text-sm "},n.member.banner.shortcodes)),s.createElement(g.Z,{icon:"arrow-down",color:"text-dark"})),s.createElement("span",{className:"border-b border-light_border w-full"}),s.createElement("div",{className:"flex flex-col w-full h-full overflow-y-auto "},n.shortcodes.content.member.banner.shortcodes.map((function(e,t){return s.createElement(v,{key:t,label:e.label,value:e.value})}))))),s.createElement(b.Z,{click:function(){return t("member")}})))};var A=n(2940),$=n(7420),L=n(679),M=n(5791),R=n(7294);const G=function(e){var t=e.image,n=e.height,r=void 0===n?"h-6":n,o=e.width,l=void 0===o?"w-6":o,a=e.objectFit,c=void 0===a?"object-contain":a,s=e.alt,i=void 0===s?"image":s;return R.createElement("img",{className:"".concat(r," ").concat(l," ").concat(c," rounded-md"),alt:i,src:t})};var B=n(9818);const H=function(e){var t=e.activePage,n=s.useContext(x.ko),r=n.commonState,o=n.appState,l=r.design,a=r.content,c=a.member,i=s.useContext(x.Gr);return s.createElement("div",{className:"flex flex-col  relative  w-full h-full "},s.createElement("div",{className:"flex w-full items-center py-2 px-3 justify-between w-full"},""==l.logo.image?s.createElement(g.Z,{fontSize:"2xl:text-xl text-md",opactity:"".concat("none"===l.logo.is_show&&"opacity-0"),icon:"wployalty_logo"}):s.createElement("img",{loading:"lazy",className:"".concat("none"===l.logo.is_show&&"opacity-0"," object-contain rounded-md h-8 w-12"),src:l.logo.image,alt:"logo_image"}),s.createElement("div",{className:"flex items-center justify-center h-8 w-8 rounded-md ",style:{background:"".concat(l.colors.theme.primary)}},s.createElement(g.Z,{icon:"close",fontSize:"2xl:text-2xl text-xl",color:"".concat((0,p.Xv)(l.colors.theme.text))}))),s.createElement("div",{className:"max-h-[360px] h-max flex flex-col py-2 px-3 gap-y-2 xl:gap-y-3 overflow-y-auto  w-full"},s.createElement("div",{className:" rounded-xl flex flex-col  justify-start px-3 py-2 w-full  shadow-card_1 transition 200ms linear",style:{backgroundColor:"".concat(l.colors.theme.primary," ")}},s.createElement("div",{className:"flex items-center justify-between w-full"},s.createElement("p",{className:"2xl:text-sm text-xs font-semibold ".concat("white"===l.colors.theme.text?"text-white":"text-black"),dangerouslySetInnerHTML:{__html:(0,p.Mb)(a.member.banner.texts.welcome)}}),"show"===a.member.banner.levels.is_show&&a.member.banner.levels.level_data.user_has_level&&o.is_pro&&s.createElement("span",{className:"flex items-center justify-between px-3 py-1  transition delay-150 ease ".concat("white"===l.colors.buttons.text?"text-white":"text-black"," rounded-md"),style:{backgroundColor:"".concat(l.colors.buttons.background," ")}},s.createElement("p",{className:"2xl:text-sm text-xs font-semibold ".concat("white"===l.colors.theme.text?"text-white":"text-black")},a.member.banner.levels.level_data.current_level_name))),"show"===a.member.banner.points.is_show&&s.createElement("div",{className:"flex gap-1 justify-start items-baseline mt-1.5"},s.createElement("p",{className:"".concat("white"===l.colors.theme.text?"text-white":"text-black"," 2xl:text-2.5xl text-2xl font-bold")},(0,p.Mb)(a.member.banner.texts.points)),s.createElement("p",{className:"2xl:text-sm text-xs font-medium ".concat("white"===l.colors.theme.text?"text-white":"text-black"),dangerouslySetInnerHTML:{__html:(0,p.Mb)(a.member.banner.texts.points_label)}})),"show"===a.member.banner.levels.is_show&&a.member.banner.levels.level_data.user_has_level&&o.is_pro&&s.createElement("div",{className:"flex flex-col w-full gap-y-1.5 xl:gap-y-2 pt-1.5"},!1===a.member.banner.levels.level_data.is_reached_final_level&&s.createElement("div",{className:"bg-[#afa3a3] h-2 w-full rounded-md relative"},s.createElement("span",{style:{width:"".concat(a.member.banner.levels.level_data.level_range,"%")},className:"absolute ".concat("white"===l.colors.theme.text?"bg-white":"bg-black"," left-0 top-0 h-2 rounded-md w-[").concat(a.member.banner.levels.level_data.level_range,"%]")})),s.createElement("p",{className:" text-xs font-normal ".concat("white"===l.colors.theme.text?"text-white":"text-black")},c.banner.levels.level_data.progress_content))),s.createElement("div",{className:"flex w-full gap-x-3 "},s.createElement("div",{onClick:function(){return t.set("earn_points")},className:"flex flex-col cursor-pointer shadow-launcher px-3 py-2 w-1/2 gap-y-2 rounded-xl shadow-card_1"},s.createElement("div",{className:"w-8 h-8 "},["",void 0].includes(c.points.earn.icon.image)?s.createElement(g.Z,{icon:"fixed-discount",fontSize:"2xl:text-3xl text-2xl"}):s.createElement(G,{width:"w-full",height:"h-full",image:c.points.earn.icon.image})),s.createElement("div",{className:"flex items-center w-full justify-between "},s.createElement("p",{className:"text-xs lg:text-sm text-dark  font-semibold ",dangerouslySetInnerHTML:{__html:(0,p.Mb)(c.points.earn.title)}}),s.createElement(g.Z,{icon:"arrow_right"}))),s.createElement("div",{onClick:function(){return t.set("redeem")},className:"flex flex-col cursor-pointer shadow-launcher px-3 py-2 w-1/2 gap-y-2 rounded-xl shadow-card_1"},s.createElement("div",{className:"w-8 h-8 "},[""].includes(c.points.redeem.icon.image)?s.createElement(g.Z,{icon:"redeem",fontSize:"2xl:text-3xl text-2xl"}):s.createElement(G,{width:"w-full",height:"h-full",image:c.points.redeem.icon.image})),s.createElement("div",{className:"flex items-center w-full justify-between "},s.createElement("p",{className:"text-xs lg:text-sm text-dark  font-semibold ",dangerouslySetInnerHTML:{__html:(0,p.Mb)(c.points.redeem.title)}}),s.createElement(g.Z,{icon:"arrow_right"})))),a.member.referrals.is_referral_action_available&&o.is_pro&&s.createElement("div",{className:"flex flex-col 2xl:gap-y-2 gap-y-1 w-full px-3 py-2.5 rounded-xl shadow-launcher "},s.createElement("p",{className:"2xl:text-sm text-xs text-dark  font-semibold ",dangerouslySetInnerHTML:{__html:(0,p.Mb)(c.referrals.title)}}),s.createElement("p",{className:"text-10px leading-4 text-light font-medium ",dangerouslySetInnerHTML:{__html:(0,p.Mb)(c.referrals.description)}}),s.createElement("div",{className:"relative border border-card_border rounded-md w-full"},s.createElement("p",{className:"p-2 h-8  whitespace-nowrap w-[87%] overflow-hidden overflow-ellipsis text-light 2xl:text-sm text-xs font-medium "},a.member.referrals.referral_url),s.createElement("span",{style:{backgroundColor:"".concat(l.colors.theme.primary," ")},className:" absolute bottom-0 flex h-8 items-center justify-center px-3 right-0 rounded-md w-8"},s.createElement("i",{className:"wlr wlrf-copy text-white font-medium "}))),s.createElement("div",{className:"flex items-center justify-center gap-x-3 lg:gap-x-4"},i.social_share_list.content.member.referrals.social_share_list.map((function(e,t){return s.createElement(g.Z,{key:t,icon:"".concat(e.action_type),color:r.design.colors.theme.primary,fontSize:"2xl:text-2xl text-xl"})}))))),s.createElement(B.Z,{show:l.branding.is_show}))},T=function(e){var t=e.activeSidebar,n=s.useContext(x.ko).commonState.launcher,r=s.useState("home"),o=(0,l.Z)(r,2),a=o[0],c=o[1];return s.createElement("div",{className:"  h-full flex flex-col gap-3  shadow-launcher overflow-hidden rounded-3xl relative",style:{fontFamily:"".concat(n.font_family),bottom:"44px"}},function(e){switch(e){case"home":return s.createElement(H,{activePage:{value:e,set:c}});case"earn_points":return s.createElement(L.Z,{activePage:{value:e,set:c}});case"redeem":return s.createElement(M.Z,{activeSidebar:t,activePage:{value:e,set:c}})}}(a))};function z(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(e);t&&(r=r.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,r)}return n}function F(e){for(var t=1;t<arguments.length;t++){var n=null!=arguments[t]?arguments[t]:{};t%2?z(Object(n),!0).forEach((function(t){(0,r.Z)(e,t,n[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(n)):z(Object(n)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(n,t))}))}return e}const q=function(e){var t=e.setActiveSidebar,n=s.useContext(x.Gr),r=s.useContext(x.ko),o=r.commonState,l=r.setCommonState,a=s.useContext(x.BE),c=a.errors,i=a.errorList,m=o.content.member.points,g=function(e){var t=F({},o);t.content.member.points[e].icon.image="",l(t)},v=function(e){var t=wp.media({title:"Select media",multiple:!1,library:{type:"image"}});return t.on("select",(function(){t.state().get("selection").each((function(t){var n=t.attributes.url,r=F({},o);r.content.member.points[e].icon.image=n,l(r)}))})),t.open(),!1},w=function(e,t,n){var r=F({},o);r.content.member.points[t][n]=e.target.value,l(r)};return s.createElement("div",null,s.createElement(u.Z,{title:n.common.points,click:function(){return t("member")}}),s.createElement("div",{className:"flex flex-col w-full h-[488px] overflow-y-auto "},s.createElement(d.Z,{title:n.common.earn},s.createElement(f.Z,{label:n.common.title,value:m.earn.title,error:i.includes("content.member.points.earn.title"),error_message:i.includes("content.member.points.earn.title")&&(0,p.e$)(c,"content.member.points.earn.title"),onChange:function(e){return w(e,"earn","title")}}),s.createElement("p",{className:"text-light text-xs 2xl:text-sm font-semibold tracking-wider"},"Icon"),s.createElement(_.Z,{value:m.earn.icon.image,handleChooseImage:function(){return v("earn")},handleRemoveImage:function(){return g("earn")}})),s.createElement(d.Z,{title:n.common.redeem},s.createElement(f.Z,{label:n.common.title,value:m.redeem.title,error:i.includes("content.member.points.redeem.title"),error_message:i.includes("content.member.points.redeem.title")&&(0,p.e$)(c,"content.member.points.redeem.title"),onChange:function(e){return w(e,"redeem","title")}}),s.createElement("p",{className:"text-light text-xs 2xl:text-sm font-semibold tracking-wider"},n.common.icon),s.createElement(_.Z,{value:m.redeem.icon.image,handleChooseImage:function(){return v("redeem")},handleRemoveImage:function(){return g("redeem")}})),s.createElement(b.Z,{click:function(){return t("member")}})))};var J=n(5183);function Y(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(e);t&&(r=r.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,r)}return n}function K(e){for(var t=1;t<arguments.length;t++){var n=null!=arguments[t]?arguments[t]:{};t%2?Y(Object(n),!0).forEach((function(t){(0,r.Z)(e,t,n[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(n)):Y(Object(n)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(n,t))}))}return e}const Q=function(){var e=s.useContext(x.Gr),t=s.useState("guest"),n=(0,l.Z)(t,2),r=n[0],a=n[1],u=s.useContext(x.ko),d=u.appState,f=u.commonState,b=u.setCommonState,v=f.design,w=f.content,_=f.launcher,y=w.member,O=(w.guest,s.useState(!0)),j=(0,l.Z)(O,2),Z=j[0],S=j[1],P=s.useState([]),D=(0,l.Z)(P,2),L=D[0],M=D[1],R=s.useState({}),G=(0,l.Z)(R,2),B=G[0],H=G[1],z=s.useState("guest"),F=(0,l.Z)(z,2),Y=F[0],Q=F[1],U=function(){var t=(0,o.Z)(c().mark((function t(){var n,r,o,l,a,s=arguments;return c().wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return n=s.length>0&&void 0!==s[0]?s[0]:d.content_nonce,r=s.length>1&&void 0!==s[1]&&s[1],o=s.length>2?s[2]:void 0,(l={wll_nonce:n,action:"wll_launcher_save_content"}).content=JSON.stringify(o),t.next=7,(0,A.j)(l);case 7:!0===(a=t.sent).data.success&&null!=a.data.data?((0,p.NQ)(r?e.common.reset_message:a.data.data.message),M([])):!1===a.data.success&&null!==a.data.data&&(H(a.data.data),(0,p.il)(a.data.data,{setErrorList:M}));case 9:case"end":return t.stop()}}),t)})));return function(){return t.apply(this,arguments)}}(),X=function(){var e=(0,o.Z)(c().mark((function e(){var t,n,r,o=arguments;return c().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return t=o.length>0&&void 0!==o[0]?o[0]:d.settings_nonce,n={wll_nonce:t,action:"wll_launcher_settings"},e.next=4,(0,A.j)(n);case 4:!0===(r=e.sent).data.success&&null!=r.data.data?(b(r.data.data),S(!1)):!1===r.data.success&&null!==r.data.data&&((0,p.NQ)(r.data.data.message,!1),S(!1));case 6:case"end":return e.stop()}}),e)})));return function(){return e.apply(this,arguments)}}();return s.useEffect((function(){X()}),[]),Z?s.createElement($.Z,null):s.createElement(x.BE.Provider,{value:{errors:B,setErrors:H,errorList:L,setErrorList:M}},s.createElement("div",{className:"w-full flex flex-col gap-y-2 items-start  "},s.createElement(i.Z,{title:e.common.content,resetAction:function(){var t=K({},f);t.content={guest:{welcome:{texts:{title:"Join and Earn Rewards",description:"Get exclusive perks by becoming a member of our rewards program.",have_account:"Already have an account?",sign_in:"Sign in",sign_in_url:"{wlr_signin_url}"},button:{text:"Join Now!",url:"{wlr_signup_url}"}},points:{earn:{title:"Earn",icon:{image:""}},redeem:{title:"Redeem",icon:{image:""}}},referrals:{title:"Refer and earn",description:"Refer your friends and earn rewards. Your friend can get a reward as well!"}},member:{banner:{levels:K(K({},w.member.banner.levels),{},{is_show:"show"}),points:{is_show:"show"},texts:{welcome:"Hello {wlr_user_name}",points:"{wlr_user_points}",points_label:"{wlr_point_label}",points_text:"Points",points_content:"Your outstanding balance"}},points:{earn:{title:"Earn",icon:{image:""}},redeem:{title:"Redeem",icon:{image:""}}},referrals:K(K({},y.referrals),{},{title:"Refer and earn",description:"Refer your friends and earn rewards. Your friend can get a reward as well!"})}},(0,p._1)((function(){b(t),U(d.content_nonce,!0,t.content)}),e.common.confirm_description,e.common.ok_text,e.common.cancel_text,e.common.confirm_title)},saveAction:function(){return U(d.content_nonce,!1,w)}}),s.createElement("div",{className:"flex gap-x-6 items-start w-full h-[590px]"},s.createElement("div",{className:"2xl:w-[30%] w-[40%]  h-full flex flex-col border border-card_border rounded-xl"},s.createElement("div",{className:"bg-primary_extra_light border border-t-0 border-r-0 border-l-0 rounded-t-xl border-b-card_border"},s.createElement("div",{className:"flex w-full items-center "},s.createElement("div",{onClick:function(){Q("guest"),a("guest")},className:"flex cursor-pointer items-center justify-center px-6 py-3 w-1/2 border-b-2 ".concat(["guest","referrals","points","welcome"].includes(r)?"border-b-blue_primary text-dark":"border-b-transparent text-light")},s.createElement("p",{className:" text-sm 2xl:text-md  font-medium tracking-wide  "},e.guest.title)),s.createElement("div",{onClick:function(){Q("member"),a("member")},className:"flex cursor-pointer items-center justify-center px-6 py-3 w-1/2 border-b-2 ".concat(["member","member_referrals","banner","member_points"].includes(r)?"border-b-blue_primary text-dark":"border-b-transparent text-light")},s.createElement("p",{className:"  text-sm 2xl:text-md  font-medium tracking-wide  "},e.member.title)))),function(){switch(r){case"guest":return s.createElement("div",null,s.createElement(m.Z,{label:e.common.welcome,tabIcon:"grammerly",click:function(){return a("welcome")}}),s.createElement(m.Z,{label:e.common.points,tabIcon:"coin",click:function(){return a("points")}}),s.createElement(m.Z,{label:e.common.referrals,tabIcon:"rocket",click:d.is_pro?function(){return a("referrals")}:function(){},isPro:d.is_pro}));case"member":return s.createElement("div",null,s.createElement(m.Z,{label:e.common.banner,tabIcon:"document_text",click:function(){return a("banner")}}),s.createElement(m.Z,{label:e.common.points,tabIcon:"coin",click:function(){return a("member_points")}}),s.createElement(m.Z,{label:e.common.referrals,tabIcon:"rocket",click:d.is_pro?function(){return a("member_referrals")}:function(){},isPro:d.is_pro}));case"welcome":return s.createElement(h,{choosedTypeUser:Y,setActiveSidebar:a,setChoosedTypeUser:Q});case"points":return s.createElement(N,{setActiveSidebar:a});case"referrals":return s.createElement(k,{setActiveSidebar:a});case"banner":return s.createElement(I,{setActiveSidebar:a});case"member_points":return s.createElement(q,{setActiveSidebar:a});case"member_referrals":return s.createElement(C,{setActiveSidebar:a})}}()),s.createElement("div",{className:"2xl:w-[70%] w-[60%]  h-[590px] flex flex-col border border-card_border rounded-xl"},s.createElement(J.Z,null),s.createElement("div",{className:"flex  items-start ".concat("left"===_.placement.position?"justify-start":"justify-end","\n                             w-full h-full  relative 2xl:px-5 md:px-3 px-2 overflow-hidden")},s.createElement("div",{className:"flex flex-col py-3 gap-y-1  w-[300px]  absolute",style:"left"===_.placement.position?{left:"".concat(+_.placement.side_spacing+16,"px"),bottom:"".concat(+_.placement.bottom_spacing+8,"px")}:{right:"".concat(+_.placement.side_spacing+16,"px"),bottom:"".concat(+_.placement.bottom_spacing+8,"px")}},"guest"===Y?s.createElement(E.Z,null):s.createElement(T,{activeSidebar:r}),s.createElement("div",{className:"text-white h-10 group cursor-pointer  flex items-center justify-center gap-x-2 p-1.5 ".concat("w-10"," absolute \n                                    ").concat("right"===_.placement.position&&"right-0"," bottom-1.5 rounded-md"),style:{backgroundColor:"".concat(v.colors.theme.primary)}},s.createElement("div",{className:"flex h-8 items-center justify-center rounded-md  group-hover:animate-swing "},"image"==_.appearance.icon.selected&&""!==_.appearance.icon.image?s.createElement("img",{src:_.appearance.icon.image,className:"object-contain rounded-md w-8 h-8  "}):s.createElement(g.Z,{icon:"".concat(_.appearance.icon.icon),text:"2xl:text-2xl text-xl",color:(0,p.Xv)(v.colors.theme.text)})))))))))}}}]);