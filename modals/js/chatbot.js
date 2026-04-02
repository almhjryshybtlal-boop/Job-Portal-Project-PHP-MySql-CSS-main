const chatbotBtn = document.querySelector(".chatbot-btn");
const chatbotCloseBtn = document.querySelector(".close-btn");
const chatArea = document.querySelector(".chatbox");
const chatInput = document.querySelector(".chat-input textarea");
const sendBtn = document.querySelector(".chat-input span");

let messages = null;
const inputHeight = chatInput.scrollHeight;

const chatList = (message, className) => {
  const chatLi = document.createElement("li");
  chatLi.classList.add("chat", className);
  let chatContent =
    className === "outgoing"
      ? "<p></p>"
      : '<span class="icon"><i class="fa-regular fa-envelope"></i></span><p></p>';
  chatLi.innerHTML = chatContent;
  chatLi.querySelector("p").textContent = message;
  return chatLi;
};

// قاعدة بيانات الردود التلقائية
const autoResponses = {
  // تحيات
  'مرحبا': 'مرحباً بك! كيف يمكنني مساعدتك في بوابة الوظائف؟',
  'السلام عليكم': 'وعليكم السلام ورحمة الله وبركاته! كيف أستطيع خدمتك؟',
  'صباح الخير': 'صباح النور! هل تبحث عن وظيفة أم لديك استفسار؟',
  'مساء الخير': 'مساء النور! كيف يمكنني مساعدتك؟',
  'hello': 'Hello! How can I help you with the job portal?',
  'hi': 'Hi there! What can I do for you today?',
  
  // أسئلة عن الوظائف
  'كيف ابحث عن وظيفة': 'يمكنك البحث عن الوظائف من خلال صفحة "تصفح الوظائف" في القائمة الرئيسية. يمكنك التصفية حسب الموقع والصناعة ونوع الوظيفة.',
  'كيف اتقدم للوظيفة': 'للتقديم على وظيفة: 1) سجل دخولك 2) اذهب لتفاصيل الوظيفة 3) اضغط "تقدم الآن" 4) تأكد من رفع سيرتك الذاتية.',
  'طريقة التقديم': 'يمكنك التقديم بالضغط على "تقدم الآن" في صفحة تفاصيل الوظيفة. تأكد من تحديث سيرتك الذاتية أولاً!',
  'jobs': 'To search for jobs, go to "Find Jobs" page and use the filters to find your dream job!',
  'apply': 'Click on "Apply Now" button on any job details page. Make sure your resume is uploaded!',
  
  // أسئلة عن التسجيل
  'كيف اسجل': 'اضغط على "تسجيل" في القائمة العلوية، اختر نوع الحساب (باحث عن عمل أو شركة)، وأدخل بياناتك.',
  'نسيت كلمة المرور': 'يمكنك استخدام خيار "نسيت كلمة المرور" في صفحة تسجيل الدخول لإعادة تعيينها.',
  'تسجيل حساب': 'للتسجيل: اضغط "تسجيل" من القائمة، اختر نوع الحساب، وأكمل البيانات المطلوبة.',
  'register': 'Click on "Register" in the menu, choose account type (Job Seeker or Company), and fill in your details.',
  'forgot password': 'Use the "Forgot Password" option on the login page to reset your password.',
  
  // أسئلة عن السيرة الذاتية
  'كيف ارفع السيرة الذاتية': 'بعد تسجيل الدخول، اذهب إلى لوحة التحكم > سيرتي الذاتية > اختر ملف PDF > ارفع.',
  'تحديث السيرة': 'اذهب إلى "سيرتي الذاتية" في لوحة التحكم، يمكنك رفع سيرة ذاتية جديدة بصيغة PDF.',
  'resume': 'Go to Dashboard > My Resume > Upload your PDF resume file.',
  'cv': 'You can upload or update your CV from the Dashboard under "My Resume" section.',
  
  // أسئلة عن الشركات
  'كيف اضيف وظيفة': 'سجل كشركة، ثم من لوحة التحكم اختر "إضافة وظيفة" وأدخل تفاصيل الوظيفة.',
  'إضافة وظيفة': 'من لوحة تحكم الشركة > إضافة وظيفة > أدخل التفاصيل > انشر.',
  'post job': 'From Company Dashboard > Add Job > Fill in the details > Publish.',
  'company': 'Companies can post jobs and manage applications through their dashboard.',
  
  // معلومات عامة
  'ما هي بوابة الوظائف': 'بوابة الوظائف هي منصة تربط الباحثين عن عمل بالشركات. يمكنك البحث والتقديم على وظائف، والشركات يمكنها نشر الوظائف واستقبال الطلبات.',
  'خدماتكم': 'نوفر: بحث عن وظائف، التقديم المباشر، رفع السيرة الذاتية، إدارة الطلبات، تقييم الشركات.',
  'about': 'Job Portal connects job seekers with companies. Search jobs, apply, upload resume, and get hired!',
  'help': 'I can help you with: job search, applying, registration, uploading resume, and more. Just ask!',
  
  // الشكر والوداع
  'شكرا': 'العفو! سعداء بخدمتك. إذا كان لديك أي استفسار آخر، لا تتردد!',
  'شكراً': 'على الرحب والسعة! نتمنى لك التوفيق في بحثك عن الوظيفة.',
  'وداعا': 'وداعاً! نتمنى لك حظاً موفقاً! 👋',
  'مع السلامة': 'مع السلامة! بالتوفيق في رحلتك المهنية!',
  'thank you': 'You\'re welcome! Good luck with your job search!',
  'thanks': 'You\'re welcome! Feel free to ask if you need more help.',
  'bye': 'Goodbye! Best of luck! 👋',
};

const generateResponse = (chatElement) => {
  const messageElement = chatElement.querySelector("p");
  const userMessage = messages.toLowerCase().trim();
  
  // البحث عن رد مناسب
  let response = null;
  
  // البحث عن تطابق كامل
  if (autoResponses[userMessage]) {
    response = autoResponses[userMessage];
  } else {
    // البحث عن تطابق جزئي
    for (const [key, value] of Object.entries(autoResponses)) {
      if (userMessage.includes(key) || key.includes(userMessage)) {
        response = value;
        break;
      }
    }
  }
  
  // إذا لم يجد رد مناسب
  if (!response) {
    response = 'عذراً، لم أفهم سؤالك. يمكنك السؤال عن: البحث عن وظائف، التقديم، التسجيل، رفع السيرة الذاتية، أو إضافة وظيفة (للشركات). يمكنك أيضاً التواصل مع الدعم الفني.';
  }
  
  // عرض الرد
  setTimeout(() => {
    messageElement.textContent = response;
    chatArea.scrollTo(0, chatArea.scrollHeight);
  }, 500);
};

const handleChat = () => {
  messages = chatInput.value.trim();
  if (!messages) return;

  chatInput.value = "";
  chatInput.style.height = `${inputHeight}px`;

  chatArea.appendChild(chatList(messages, "outgoing"));
  chatArea.scrollTo(0, chatArea.scrollHeight);

  setTimeout(() => {
    const incomingChatLi = chatList("...", "incoming");
    chatArea.appendChild(incomingChatLi);
    chatArea.scrollTo(0, chatArea.scrollHeight);
    generateResponse(incomingChatLi);
  }, 100);
};

chatInput.addEventListener("input", () => {
  chatInput.style.height = `${inputHeight}px`;
  chatInput.style.height = `${chatInput.scrollHeight}px`;
});

sendBtn.addEventListener("click", handleChat);

chatbotBtn.addEventListener("click", () =>
  document.body.classList.toggle("show-chatbot")
);

chatbotCloseBtn.addEventListener("click", () =>
  document.body.classList.remove("show-chatbot")
);

chatInput.addEventListener("keydown", (e) => {
  if (e.key === "Enter" && !e.shiftKey && window.innerWidth > 800) {
    e.preventDefault();
    handleChat();
  }
});
