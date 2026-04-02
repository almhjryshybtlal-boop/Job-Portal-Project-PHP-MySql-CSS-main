// نظام التقييم بالنجوم - صفحة تفاصيل الشركة
document.addEventListener('DOMContentLoaded', function() {
    const ratingInputs = document.querySelectorAll('input[type=\"radio\"][name^=\"rate\"]');
    const reviewForm = document.querySelector('.review-form');
    
    if (ratingInputs.length > 0 && reviewForm) {
        // إخفاء نموذج المراجعة في البداية
        reviewForm.style.display = 'none';
        
        // إضافة حدث النقر على كل نجمة
        ratingInputs.forEach(function(input) {
            const label = document.querySelector(`label[for="${input.id}"]`);
            
            if (label) {
                label.addEventListener('click', function() {
                    // إظهار نموذج المراجعة عند اختيار أي نجمة
                    reviewForm.style.display = 'block';
                    
                    // تحديد النجمة المختارة
                    input.checked = true;
                    
                    // إضافة تأثير بصري
                    const ratingValue = input.id.split('-')[1];
                    updateStarDisplay(ratingValue);
                });
            }
        });
        
        // وظيفة لتحديث عرض النجوم
        function updateStarDisplay(selectedRating) {
            ratingInputs.forEach(function(input) {
                const label = document.querySelector(`label[for="${input.id}"]`);
                const starValue = input.id.split('-')[1];
                
                if (label) {
                    if (parseInt(starValue) <= parseInt(selectedRating)) {
                        label.style.color = '#ffa500'; // لون ذهبي للنجوم المختارة
                    } else {
                        label.style.color = '#ccc'; // لون رمادي للنجوم غير المختارة
                    }
                }
            });
        }
        
        // تأثير التحويم (Hover)
        ratingInputs.forEach(function(input) {
            const label = document.querySelector(`label[for="${input.id}"]`);
            
            if (label) {
                label.addEventListener('mouseenter', function() {
                    const ratingValue = input.id.split('-')[1];
                    highlightStars(ratingValue);
                });
                
                label.addEventListener('mouseleave', function() {
                    resetStarsToSelected();
                });
            }
        });
        
        // تمييز النجوم عند التحويم
        function highlightStars(hoverValue) {
            ratingInputs.forEach(function(input) {
                const label = document.querySelector(`label[for="${input.id}"]`);
                const starValue = input.id.split('-')[1];
                
                if (label) {
                    if (parseInt(starValue) <= parseInt(hoverValue)) {
                        label.style.color = '#ffa500';
                        label.style.transform = 'scale(1.2)';
                        label.style.transition = 'all 0.2s ease';
                    } else {
                        label.style.color = '#ccc';
                        label.style.transform = 'scale(1)';
                    }
                }
            });
        }
        
        // إعادة النجوم للحالة المختارة
        function resetStarsToSelected() {
            const checkedInput = document.querySelector('input[type=\"radio\"][name^=\"rate\"]:checked');
            
            if (checkedInput) {
                const selectedValue = checkedInput.id.split('-')[1];
                updateStarDisplay(selectedValue);
            } else {
                // إذا لم يتم اختيار أي نجمة، إعادة الكل للون الرمادي
                ratingInputs.forEach(function(input) {
                    const label = document.querySelector(`label[for="${input.id}"]`);
                    if (label) {
                        label.style.color = '#ccc';
                        label.style.transform = 'scale(1)';
                    }
                });
            }
        }
        
        // التحقق من النموذج قبل الإرسال
        const submitButton = reviewForm.querySelector('button[type=\"submit\"]');
        if (submitButton) {
            submitButton.addEventListener('click', function(e) {
                const checkedRating = document.querySelector('input[type=\"radio\"][name^=\"rate\"]:checked');
                const reviewText = reviewForm.querySelector('textarea[name=\"company-review\"]');
                
                if (!checkedRating) {
                    e.preventDefault();
                    alert('يرجى اختيار تقييم بالنجوم');
                    return false;
                }
                
                if (reviewText && reviewText.value.trim() === '') {
                    e.preventDefault();
                    alert('يرجى كتابة مراجعتك');
                    return false;
                }
            });
        }
    }
});
