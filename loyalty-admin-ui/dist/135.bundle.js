"use strict";(self.webpackChunkreact_wordpress=self.webpackChunkreact_wordpress||[]).push([[135],{3749:(e,t,a)=>{a.r(t),a.d(t,{default:()=>f});var n=a(7294),i=a(6550),l=a(3972),c=a(3089);const r=function(e){var t=e.click,a=n.useContext(l.Gr);return n.createElement("div",{className:"pt-1 pb-0.5 cursor-pointer text-xl text-red-600 max-w-max",onClick:t,style:{paddingRight:"6.5px",paddingLeft:"6.5px"},title:a.common.delete_text},n.createElement("i",{className:"wlr wlrf-delete hover:text-redd color-important text-light  text-sm 2xl:text-md leading-3"}))},o=function(e){var t=e.click,a=n.useContext(l.Gr);return n.createElement("div",{className:"pt-1 pb-0.5  cursor-pointer text-xl text-textColor max-w-max",onClick:t,style:{paddingRight:"6.5px",paddingLeft:"6.5px"},title:a.common.edit_text},n.createElement("i",{className:"wlr wlrf-edit-3 hover:text-primary text-light  text-sm 2xl:text-md  leading-3 color-important "}))};var s=a(261);const d=function(e){var t=e.span,a=e.edit_to,d=e.delete_to,m=e.click,p=e.item,x=e.duplicate_action,u=(0,n.useContext)(l.Gr),f=(0,n.useContext)(l.jZ),w=(0,i.k6)();return n.createElement("div",{className:"grid ".concat(t,"   text-textColor text-15px \n        font-medium w-11/12 antialiased overflow-ellipsis\n         overflow-hidden justify-items-end whitespace-nowrap")},n.createElement("div",{className:"flex  items-center ".concat(f?"justify-end":"justify-center","  space-x-5  w-full")},f&&n.createElement(s.Z,{click:function(){x(p.id)}}),(0,c.S1)(f,p.action_type)&&n.createElement(o,{click:function(){w.push(a)}}),n.createElement(r,{click:function(){m(p.id,d,u.earn_campaign.delete_alert_message,u.earn_campaign.delete_ok,u.earn_campaign.delete_cancel,u.earn_campaign.delete_campaign)}})))};var m=a(1257),p=a(1489),x=a(4264),u=(a(8921),a(4215));const f=function(e){var t=e.keys,a=e.rewardTitle,r=e.campaignType,o=e.active,s=e.edit_to,f=void 0===s?"edit_earn_campaign/subtotal/0":s,w=e.enable_disable_toggle,v=void 0===w?null:w,g=e.delete_to,h=void 0===g?null:g,_=e.item,k=e.AddCheck,E=e.end_date,y=e.AllCheckList,C=e.duplicateCampaign,N=(0,i.k6)(),b=(0,n.useContext)(l.jZ),j=(0,n.useContext)(l.Gr),T=(0,n.useContext)(l.ko).appState;return n.createElement("div",{className:"grid grid-cols-12 gap-4 w-full min-w-full py-4  border border-light_border rounded-lg shadow-card  ",style:{minWidth:"1024px"},key:t},n.createElement(m.Z,{checked:y.includes(_.id),click:function(){return k(_.id)}}),n.createElement((function(e){var t=e.span,a=e.rewardTitle,i=e.description,l=e.icon;return e.id,e.created_date,n.createElement("div",{className:" ".concat(t," antialiased  overflow-hidden")},n.createElement("div",{className:"flex items-center justify-start  gap-x-2 w-full overflow-hidden "},n.createElement("div",{className:"flex items-center justify-center ",style:{minWidth:"40px",maxWidth:"40px"}},["",null,"null",void 0].includes(l)?n.createElement("i",{className:"wlr wlrf-".concat(_.action_type," text-2xl 2xl:text-3xl text-primary p-0.5 leading-0 color-important h-10 ")}):n.createElement("img",{src:l,alt:"campaign_image_preview",className:" object-cover p-0.5 rounded-md h-10 w-10"})),n.createElement("div",{className:"flex flex-col items-start space-y-2 w-[90%]"},n.createElement("h5",{className:"text-dark cursor-pointer text-sm 2xl:text-md_16_l_18 font-semibold whitespace-pre overflow-hidden overflow-ellipsis w-[95%]",onClick:function(e){if(e.ctrlKey){var t="".concat(T.local.common.edit_campaign_url,"/").concat(_.action_type,"/").concat(_.id);window.open(t,"_blank")}else(0,c.S1)(b,_.action_type)&&N.push(f)},title:a},a),n.createElement("p",{title:i,className:" text-light text-xs 2xl:text-sm font-normal opacity-75 overflow-hidden overflow-ellipsis whitespace-pre w-[95%] "},i))))}),{span:"col-span-4",id:_.id,created_date:_.created_at,rewardTitle:a,description:_.description,icon:_.icon}),n.createElement((function(e){var t=e.span,a=e.campaignType;return n.createElement("p",{className:" gird ".concat(t," text-dark text-xs 2xl:text-sm  font-medium w-8/12 antialiased overflow-ellipsis whitespace-nowrap overflow-hidden self-center")},a)}),{span:"col-span-2",campaignType:r}),n.createElement((function(e){var t=e.span,a=e.campaign_date;return n.createElement("p",{title:a,className:" gird ".concat(t," text-dark text-xs 2xl:text-sm  font-medium  antialiased overflow-ellipsis whitespace-nowrap overflow-hidden self-center")},a)}),{span:"col-span-1",campaign_date:E}),n.createElement((function(e){var t=e.span;return n.createElement("div",{className:" gird ".concat(t," self-center")},n.createElement(x.Z,{active:o,height:"2xl:h-4 h-3",width:"2xl:w-4 w-3",containerWidth:"w-8 2xl:w-9",containerHeight:"h-4 2xl:h-5",click:function(){(0,c.S1)(b,_.action_type)?v(_.id,_.active):alertify.error(j.common.premium)}}))}),{span:"col-span-1 "}),n.createElement(u.Fl,{span:"col-span-1",id:_.id,createdDate:_.created_at}),n.createElement(d,{span:"col-span-2 ",duplicate_action:C,edit_to:f,item:_,delete_to:h,click:p.l}))}},261:(e,t,a)=>{a.d(t,{Z:()=>l});var n=a(7294),i=a(3972);const l=function(e){var t=e.click,a=n.useContext(i.Gr);return n.createElement("div",{className:"pt-1 pb-0.5  cursor-pointer text-xl text-textColor max-w-max",onClick:t,style:{paddingRight:"6.5px",paddingLeft:"6.5px"},title:a.common.duplicate_text},n.createElement("i",{className:"wlr wlrf-copy hover:text-primary text-light  text-sm 2xl:text-md leading-3 color-important "}))}}}]);