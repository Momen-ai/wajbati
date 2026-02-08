
const translations = {
  en: {
    home: "Home","meals":"Meals","families":"Families","contact":"Contact",
    welcome:"Welcome to Wajbati", hero_sub:"Find homemade meals from local families",
    browse_meals:"Browse Meals","view":"View","persons":"persons","order_now":"Order now","quantity":"Quantity",
    add_to_cart:"Add to cart","login_to_order":"Login to order","cart":"Cart","cart_empty":"Your cart is empty",
    meal:"Meal","qty":"Qty","price":"Price","remove":"Remove","checkout":"Checkout","address":"Address",
    create_order:"Create Order","login":"Login","register":"Register","name":"Name","phone":"Phone",
    password:"Password","password_confirm":"Confirm Password","role":"Role","customer":"Customer","family":"Family",
    users:"Users","orders":"Orders","image":"Image","user":"User","total":"Total","status":"Status",
    my_meals:"My Meals","add_meal":"Add Meal","profile":"Profile","logout":"Logout","order":"Order",
    my_orders:"My Orders","no_orders":"No orders yet","contact_info":"Contact Info","contact_details":"Email: contact@wajbati.com","send":"Send","message":"Message","admin_dashboard":"Admin Dashboard","family_dashboard":"Family Dashboard"
  },
  ar: {
    home: "الرئيسية","meals":"الوجبات","families":"العائلات","contact":"اتصل بنا",
    welcome:"مرحبا في وجباتي", hero_sub:"ابحث عن وجبات منزلية محلية",
    browse_meals:"تصفح الوجبات","view":"عرض","persons":"شخص","order_now":"اطلب الآن","quantity":"الكمية",
    add_to_cart:"أضف للسلة","login_to_order":"سجل لتتمكن من الطلب","cart":"السلة","cart_empty":"السلة فارغة",
    meal:"الوجبة","qty":"الكمية","price":"السعر","remove":"حذف","checkout":"الدفع","address":"العنوان",
    create_order:"إنشاء الطلب","login":"تسجيل الدخول","register":"التسجيل","name":"الاسم","phone":"الهاتف",
    password:"كلمة المرور","password_confirm":"تأكيد كلمة المرور","role":"الدور","customer":"زبون","family":"عائلة",
    users:"المستخدمون","orders":"الطلبات","image":"الصورة","user":"العميل","total":"المجموع","status":"الحالة",
    my_meals:"وجباتي","add_meal":"أضف وجبة","profile":"الملف الشخصي","logout":"تسجيل الخروج","order":"طلب",
    my_orders:"طلباتي","no_orders":"لا توجد طلبات","contact_info":"معلومات الاتصال","contact_details":"البريد: contact@wajbati.com","send":"إرسال","message":"الرسالة","admin_dashboard":"لوحة التحكم","family_dashboard":"لوحة العائلة"
  }
};

function setLanguage(lang){
  document.documentElement.lang = (lang==='ar') ? 'ar' : 'en';
  document.documentElement.dir = (lang==='ar') ? 'rtl' : 'ltr';
  document.querySelectorAll('[data-key]').forEach(function(el){
    const key = el.getAttribute('data-key');
    if(translations[lang] && translations[lang][key]) el.innerText = translations[lang][key];
  });
  const btn = document.getElementById('langToggle');
  if(btn) btn.innerText = (lang==='ar') ? 'EN' : 'ع';
  localStorage.setItem('wajbati_lang', lang);
}

document.addEventListener('DOMContentLoaded', function(){
  let lang = localStorage.getItem('wajbati_lang') || 'en';
  setLanguage(lang);
  const btn = document.getElementById('langToggle');
  if(btn){
    btn.addEventListener('click', function(e){
      e.preventDefault();
      lang = (document.documentElement.lang==='ar') ? 'en' : 'ar';
      setLanguage(lang);
    });
  }
});
