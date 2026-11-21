<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>গোপনীয়তা নীতি (Privacy Policy) – PrintSheba</title>
    <meta name="description" content="PrintSheba-এর জিরো ডাটা রিটেনশন প্রাইভেসি পলিসি। প্রিন্ট শেষ হওয়ামাত্র সার্ভার ও লোকাল ডিভাইস থেকে ফাইল মুছে ফেলা হয়।">
    <link rel="canonical" href="{{ url('/privacy-policy') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>body { font-family: 'Hind Siliguri', sans-serif; }</style>
</head>
<body class="bg-slate-50 text-slate-800">
    @include('partials.header')

    <main class="max-w-4xl mx-auto px-4 py-16">
        <div class="bg-white p-8 sm:p-12 rounded-3xl border border-slate-200 shadow-sm space-y-6">
            <div class="border-b border-slate-100 pb-6">
                <span class="text-xs font-black text-rose-600 uppercase tracking-wider block mb-1">প্রাইভেসি ও ডেটা সিকিউরিটি</span>
                <h1 class="text-3xl font-black text-slate-900">গোপনীয়তা নীতি (Privacy Policy)</h1>
                <p class="text-xs text-slate-400 mt-1">সর্বশেষ আপডেট: ২১ নভেম্বর ২০২৬</p>
            </div>

            <div class="space-y-6 text-sm text-slate-600 leading-relaxed">
                <div>
                    <h2 class="text-base font-bold text-slate-900 mb-2">১. জিরো-ফাইল রিটেনশন পলিসি (Zero Data Retention)</h2>
                    <p>PrintSheba প্ল্যাটফর্মে কাস্টমারের যেকোনো ডকুমেন্টের পূর্ণ গোপনীয়তা বজায় রাখা আমাদের সর্বোচ্চ অঙ্গীকার। কাস্টমার যে ফাইলটি প্রিন্ট করার জন্য আপলোড করেন, সেটি প্রিন্টারে প্রিন্ট সম্পন্ন হওয়ামাত্রই সার্ভার এবং দোকানদারের কম্পিউটার থেকে স্বয়ংক্রিয়ভাবে চিরতরে ডিলিট (Delete) হয়ে যায়। কোনো ফাইল আমাদের সার্ভারে ব্যাকআপ হিসেবে রাখা হয় না।</p>
                </div>

                <div>
                    <h2 class="text-base font-bold text-slate-900 mb-2">২. যেসকল তথ্য আমরা সংগ্রহ করি</h2>
                    <p>দোকানদার এবং ব্যবহারকারীদের রেজিস্ট্রেশনের সুবিধার্থে শুধুমাত্র প্রয়োজনীয় তথ্য সংগ্রহ করা হয়:</p>
                    <ul class="list-disc list-inside mt-2 space-y-1 pl-2 text-xs">
                        <li>দোকানের নাম, পরিচালকের নাম ও মোবাইল নম্বর</li>
                        <li>অ্যাকাউন্ট ভেরিফিকেশন ও পাসওয়ার্ড রিসেটের জন্য ইমেইল ঠিকানা</li>
                        <li>পেমেন্ট ম্যাচিংয়ের জন্য বিকাশ/নগদ ট্রানজেকশন আইডি (TrxID)</li>
                    </ul>
                </div>

                <div>
                    <h2 class="text-base font-bold text-slate-900 mb-2">৩. মোবাইল ক্যামেরা ও ফাইল পারমিশন</h2>
                    <p>কাস্টমার কিউআর কোড স্ক্যান করার জন্য ব্রাউজারের ক্যামেরা পারমিশন এবং ফাইল আপলোডের জন্য ডিভাইসের স্টোরেজ অ্যাক্সেস নেওয়া হয়। এই পারমিশন শুধুমাত্র তৎক্ষণাৎ প্রিন্ট কার্য সম্পাদনের জন্য ব্যবহৃত হয় এবং কোনো ব্যাকগ্রাউন্ড ট্র্যাকিং করা হয় না।</p>
                </div>

                <div>
                    <h2 class="text-base font-bold text-slate-900 mb-2">৪. যোগাযোগ ও জিজ্ঞাসা</h2>
                    <p>প্রাইভেসি সংক্রান্ত যেকোনো প্রশ্ন থাকলে আমাদের হটলাইনে (<a href="tel:01644416378" class="text-rose-600 font-bold">01644416378</a>) অথবা ইমেইলে (<a href="mailto:masud.info@gmail.com" class="text-rose-600 underline">masud.info@gmail.com</a>) সরাসরি যোগাযোগ করতে পারেন।</p>
                </div>
            </div>
        </div>
    </main>

    @include('partials.footer')
</body>
</html>

