"use strict";(self.webpackChunkreact_wordpress=self.webpackChunkreact_wordpress||[]).push([[589],{6589:(e,t,a)=>{a.r(t),a.d(t,{default:()=>m});var n=a(2982),r=a(4942),i=a(6459),l=a(7294),s=a(6478),o=a(3972),_=a(6798),d=a(8991),u=a(3089);function p(e,t){var a=Object.keys(e);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(e);t&&(n=n.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),a.push.apply(a,n)}return a}function c(e){for(var t=1;t<arguments.length;t++){var a=null!=arguments[t]?arguments[t]:{};t%2?p(Object(a),!0).forEach((function(t){(0,r.Z)(e,t,a[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(a)):p(Object(a)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(a,t))}))}return e}const m=function(e){(0,i.Z)(e);var t=l.useContext(o.Gr),a=l.useContext(o.QN),r=a.State,p=a.setState,m=a.localState,g=a.errorList,f=a.errors,w=[{label:t.common.select.round,value:"round"},{label:t.common.select.round_floor,value:"floor"},{label:t.common.select.ceil,value:"ceil"}],b=[{value:"yes",label:t.common.select.yes},{value:"no",label:t.common.select.no}],v=[{value:"yes",label:t.common.select.yes},{value:"no",label:t.common.select.no}],y=[{value:"yes",label:t.calculate_point.after_discount},{value:"no",label:t.calculate_point.before_discount}];return l.createElement("div",{className:"flex flex-col items-start w-full mt-3 xl:mt-4 2xl:mt-5"},l.createElement(_.Z,{label:t.settings.earn_point.name,description:t.settings.earn_point.description}),l.createElement(s.Z,{id:"earn_points_wlr_rounding_type",empty:!0,width:"w-full",label:t.settings.earn_point.earn_point.name,description:t.settings.earn_point.earn_point.description,options:w,value:r.data.wlr_point_rounding_type,error:g.includes("wlr_point_rounding_type"),onChange:function(e){var t=c(c({},r),{},{data:c(c({},r.data),{},{wlr_point_rounding_type:e.value})});p(t)},error_message:g.includes("wlr_point_rounding_type")&&(0,u.e$)(f,"wlr_point_rounding_type")}),l.createElement(s.Z,{id:"earn_points_earning_status",empty:!0,label:t.settings.earn_point.earn_order.name,description:t.settings.earn_point.earn_order.description,width:"w-full",options:m.local.purchase_point.order_status_list,error:g.includes("order_status_list"),value:(0,u.fn)(m.local.purchase_point.order_status_list,r.data.wlr_earning_status),onChange:function(e){p(c(c({},r),{},{data:c(c({},r.data),{},{wlr_earning_status:(0,n.Z)(e.map((function(e){return e.value})))})}))},error_message:g.includes("wlr_earning_status")&&(0,u.e$)(f,"wlr_earning_status"),isMulti:!0}),l.createElement(s.Z,{id:"earn_points_failed_status",empty:!0,label:t.settings.earn_point.failed_order.name,description:t.settings.earn_point.failed_order.description,width:"w-full",options:m.local.purchase_point.order_status_list,error:g.includes("order_status_list"),value:(0,u.fn)(m.local.purchase_point.order_status_list,r.data.wlr_removing_status),onChange:function(e){p(c(c({},r),{},{data:c(c({},r.data),{},{wlr_removing_status:(0,n.Z)(e.map((function(e){return e.value})))})}))},isMulti:!0,error_message:g.includes("wlr_removing_status")&&(0,u.e$)(f,"wlr_removing_status")}),l.createElement("div",{className:"flex items-start w-full space-x-4"},l.createElement(d.Z,{id:"earn_points_point_label",empty:!0,label:t.settings.earn_point.point_text.name,description:t.settings.earn_point.point_text.description,value:r.data.wlr_point_label,error:g.includes("wlr_point_label"),onChange:function(e){p(c(c({},r),{},{data:c(c({},r.data),{},{wlr_point_label:e.target.value})}))},error_message:g.includes("wlr_point_label")&&(0,u.e$)(f,"wlr_point_label")}),l.createElement(d.Z,{id:"earn_points_point_singular_label",empty:!0,label:t.settings.earn_point.point_text_singular.name,description:t.settings.earn_point.point_text_singular.description,value:r.data.wlr_point_singular_label,error:g.includes("wlr_point_singular_label"),onChange:function(e){p(c(c({},r),{},{data:c(c({},r.data),{},{wlr_point_singular_label:e.target.value})}))},error_message:g.includes("wlr_point_singular_label")&&(0,u.e$)(f,"wlr_point_singular_label")})),l.createElement("div",{className:"flex items-start w-full space-x-4"},l.createElement(d.Z,{id:"earn_points_reward_plural_label",empty:!0,label:t.settings.earn_point.reward_text_plural.name,description:t.settings.earn_point.reward_text_plural.description,value:r.data.reward_plural_label,error:g.includes("reward_plural_label"),onChange:function(e){p(c(c({},r),{},{data:c(c({},r.data),{},{reward_plural_label:e.target.value})}))},error_message:g.includes("reward_plural_label")&&(0,u.e$)(f,"reward_plural_label")}),l.createElement(d.Z,{id:"earn_points_reward_singular_label",empty:!0,label:t.settings.earn_point.reward_text_singular.name,description:t.settings.earn_point.reward_text_singular.description,value:r.data.reward_singular_label,error:g.includes("reward_singular_label"),onChange:function(e){p(c(c({},r),{},{data:c(c({},r.data),{},{reward_singular_label:e.target.value})}))},error_message:g.includes("reward_singular_label")&&(0,u.e$)(f,"reward_singular_label")})),l.createElement("div",{className:"flex items-start w-full space-x-4  "},l.createElement(d.Z,{id:"earn_points_reward_code_prefix",empty:!0,label:t.settings.earn_point.prefix_reward.name,description:t.settings.earn_point.prefix_reward.description,value:r.data.reward_code_prefix,error:g.includes("reward_code_prefix"),onChange:function(e){p(c(c({},r),{},{data:c(c({},r.data),{},{reward_code_prefix:e.target.value})}))},error_message:g.includes("reward_code_prefix")&&(0,u.e$)(f,"reward_code_prefix")}),l.createElement(d.Z,{id:"earn_points_referral_prefix",empty:!0,label:t.settings.earn_point.prefix_referral.name,description:t.settings.earn_point.prefix_referral.description,value:r.data.wlr_referral_prefix,error:g.includes("wlr_referral_prefix"),onChange:function(e){p(c(c({},r),{},{data:c(c({},r.data),{},{wlr_referral_prefix:e.target.value})}))},error_message:g.includes("wlr_referral_prefix")&&(0,u.e$)(f,"wlr_referral_prefix")})),l.createElement("div",{className:"flex justify-start items-start space-x-4  w-full"},l.createElement(s.Z,{width:"w-full ",id:"earn_points_user_action_list",empty:!0,label:t.settings.earn_point.add_customer_wpl_customer.name,description:t.settings.earn_point.add_customer_wpl_customer.description,options:m.local.purchase_point.customer_add_action_list,error:g.includes("user_action_list"),value:(0,u.fn)(m.local.purchase_point.customer_add_action_list,r.data.user_action_list),onChange:function(e){p(c(c({},r),{},{data:c(c({},r.data),{},{user_action_list:(0,n.Z)(e.map((function(e){return e.value})))})}))},isMulti:!0,error_message:g.includes("user_action_list")&&(0,u.e$)(f,"user_action_list")}),l.createElement(s.Z,{id:"individual_use_coupon",empty:!0,width:"w-full",label:t.settings.earn_point.individual.name,description:t.settings.earn_point.individual.description,options:v,value:r.data.individual_use_coupon,error:g.includes("individual_use_coupon"),onChange:function(e){var t=c(c({},r),{},{data:c(c({},r.data),{},{individual_use_coupon:e.value})});p(t)},error_message:g.includes("individual_use_coupon")&&(0,u.e$)(f,"individual_use_coupon")})),l.createElement("div",{className:"flex items-start w-full space-x-4"},l.createElement(s.Z,{width:"w-1/2",id:"earn_points_allow_auto_generate",empty:!0,label:t.settings.earn_point.automatic_create_coupon.name,description:t.settings.earn_point.automatic_create_coupon.description,value:r.data.allow_auto_generate_coupon,options:v,error:g.includes("allow_auto_generate_coupon"),onChange:function(e){var t=c(c({},r),{},{data:c(c({},r.data),{},{allow_auto_generate_coupon:e.value})});p(t)},error_message:g.includes("allow_auto_generate_coupon")&&(0,u.e$)(f,"allow_auto_generate_coupon")}),l.createElement(s.Z,{width:"w-1/2",id:"revert_point_settings",empty:!0,label:t.settings.earn_point.revert_point.name,description:t.settings.earn_point.revert_point.description,value:r.data.is_revert_enabled,options:v,error:g.includes("is_revert_enabled"),onChange:function(e){var t=c(c({},r),{},{data:c(c({},r.data),{},{is_revert_enabled:e.value})});p(t)},error_message:g.includes("is_revert_enabled")&&(0,u.e$)(f,"is_revert_enabled")})),l.createElement("div",{className:"flex items-start w-full space-x-4 "},l.createElement(s.Z,{width:"w-1/2",id:"debug_mode",empty:!0,label:t.settings.earn_point.debug_mode.name,description:t.settings.earn_point.debug_mode.description,value:r.data.debug_mode,options:b,error:g.includes("debug_mode"),onChange:function(e){var t=c(c({},r),{},{data:c(c({},r.data),{},{debug_mode:e.value})});p(t)},error_message:g.includes("debug_mode")&&(0,u.e$)(f,"debug_mode")}),l.createElement(s.Z,{id:"pagination_limit",empty:!0,width:"w-1/2",label:t.settings.earn_point.pagination_limit.name,description:t.settings.earn_point.pagination_limit.description,options:[{value:5,label:5},{value:10,label:10},{value:20,label:20},{value:50,label:50},{value:100,label:100}],value:r.data.pagination_limit,error:g.includes("pagination_limit"),onChange:function(e){var t=c(c({},r),{},{data:c(c({},r.data),{},{pagination_limit:e.value})});p(t)},error_message:g.includes("pagination_limit")&&(0,u.e$)(f,"pagination_limit")})),l.createElement("div",{className:"flex w-full items-start space-x-4 "},l.createElement(s.Z,{width:"w-1/2",id:"is_earn_point_after_discount_id",empty:!0,label:t.settings.earn_point.is_earn_point_after_discount.name,description:t.settings.earn_point.is_earn_point_after_discount.description,value:r.data.is_earn_point_after_discount,options:y,error:g.includes("is_earn_point_after_discount"),onChange:function(e){var t=c(c({},r),{},{data:c(c({},r.data),{},{is_earn_point_after_discount:e.value})});p(t)},error_message:g.includes("is_earn_point_after_discount")&&(0,u.e$)(f,"is_earn_point_after_discount")}),l.createElement(s.Z,{width:"w-1/2",id:"birthday_display_place_id",empty:!0,isMulti:!0,label:t.settings.earn_point.display_birthday_date_at.name,description:t.settings.earn_point.display_birthday_date_at.description,value:(0,u.fn)(t.settings.earn_point.display_birthday_date_at.options,r.data.birthday_display_place),options:t.settings.earn_point.display_birthday_date_at.options,error:g.includes("birthday_display_place"),onChange:function(e){var t=c(c({},r),{},{data:c(c({},r.data),{},{birthday_display_place:(0,n.Z)(e.map((function(e){return e.value})))})});p(t)},error_message:g.includes("birthday_display_place")&&(0,u.e$)(f,"birthday_display_place")})))}},6798:(e,t,a)=>{a.d(t,{Z:()=>i});var n=a(7294),r=a(678);const i=function(e){var t=e.label,a=e.description;return n.createElement("div",{className:"flex flex-col space-y-1"},n.createElement("h4",{className:"text-dark font-semibold text-lg tracking-wide "},t),n.createElement(r.Z,null,a))}}}]);