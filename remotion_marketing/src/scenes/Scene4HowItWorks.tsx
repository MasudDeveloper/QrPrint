import React from 'react';
import { interpolate, spring, useCurrentFrame, useVideoConfig } from 'remotion';
import { Background } from '../components/Background';
import { Badge, Title } from '../components/Typography';
import { AnimatedPrinter, AnimatedQrScanner } from '../components/SvgIcons';

export const Scene4HowItWorks: React.FC = () => {
  const frame = useCurrentFrame();
  const { fps } = useVideoConfig();

  // 0s - 3.0s: "প্রিন্ট করা এখন পানির মতো সহজ—"
  const titleAnim = spring({ frame: frame - 10, fps, config: { damping: 14 } });

  // 3.0s - 8.5s: "প্রথম ধাপ: কাস্টমার দোকানে টানানো কিউআর কোডটি মোবাইল ক্যামেরা দিয়ে স্ক্যান করবে"
  const step1 = spring({ frame: frame - 90, fps, config: { damping: 14 } });

  // 8.5s - 16.5s: "দ্বিতীয় ধাপ: চোখের পলকে ফাইল আপলোড করে পেজ সংখ্যা ও কালার সিলেক্ট করলেই স্বয়ংক্রিয়ভাবে টাকার হিসাব চলে আসবে"
  const step2 = spring({ frame: frame - 255, fps, config: { damping: 14 } });
  const countAnim = interpolate(frame, [310, 420], [0, 10], {
    extrapolateLeft: 'clamp',
    extrapolateRight: 'clamp',
  });
  const billAnim = interpolate(frame, [350, 450], [0, 50], {
    extrapolateLeft: 'clamp',
    extrapolateRight: 'clamp',
  });

  // 16.5s - 24.5s: "আর তৃতীয় ধাপ: বিকাশ, নগদ বা ক্যাশে পেমেন্ট কনফার্ম হলেই আপনার প্রিন্টার থেকে সাথে সাথে কাগজ প্রিন্ট হয়ে বের হয়ে আসবে!"
  const step3 = spring({ frame: frame - 495, fps, config: { damping: 14 } });

  // Floating micro animation
  const floatY = Math.sin(frame * 0.04) * 6;

  return (
    <div
      style={{
        position: 'absolute',
        inset: 0,
        display: 'flex',
        flexDirection: 'column',
        alignItems: 'center',
        justifyContent: 'center',
        padding: '0 60px',
        color: '#ffffff',
      }}
    >
      <Background variant="tech" />

      {/* Top Badge */}
      <div style={{ marginBottom: 20 }}>
        <Badge text="ব্যবহারের সহজ পদ্ধতি" icon="🚀" color="blue" delay={5} />
      </div>

      <Title size={48} delay={10}>
        প্রিন্ট করা এখন পানির মতো সহজ!
      </Title>
      <div style={{ marginTop: 8 }}>
        <Title size={60} gradient="cyan" delay={25}>
          মাত্র ৩টি সহজ ধাপে সুপারফাস্ট প্রিন্ট
        </Title>
      </div>

      {/* 3 Step Cards */}
      <div
        style={{
          display: 'grid',
          gridTemplateColumns: 'repeat(3, 1fr)',
          gap: 30,
          marginTop: 50,
          width: '100%',
          maxWidth: 1550,
          transform: `translateY(${floatY}px)`,
        }}
      >
        {/* Step 1: Scan (Enters at 3.0s) */}
        <div
          style={{
            transform: `translateY(${interpolate(step1, [0, 1], [50, 0])}px) scale(${step1})`,
            opacity: step1,
            backgroundColor: 'rgba(15, 23, 42, 0.85)',
            border: '2px solid rgba(59, 130, 246, 0.4)',
            borderRadius: 32,
            padding: 30,
            display: 'flex',
            flexDirection: 'column',
            alignItems: 'center',
            textAlign: 'center',
            backdropFilter: 'blur(20px)',
            boxShadow: '0 20px 40px rgba(0,0,0,0.6)',
          }}
        >
          <div
            style={{
              padding: '6px 18px',
              borderRadius: 9999,
              backgroundColor: '#3b82f6',
              color: '#ffffff',
              fontSize: 16,
              fontWeight: 900,
              fontFamily: "'Plus Jakarta Sans', sans-serif",
              marginBottom: 20,
            }}
          >
            STEP 01
          </div>
          <AnimatedQrScanner size={170} />
          <h3
            style={{
              fontSize: 26,
              fontWeight: 900,
              color: '#ffffff',
              marginTop: 20,
              fontFamily: "'Hind Siliguri', sans-serif",
            }}
          >
            ১. কিউআর স্ক্যান করুন
          </h3>
          <p
            style={{
              fontSize: 17,
              color: '#94a3b8',
              lineHeight: 1.5,
              marginTop: 10,
              fontFamily: "'Hind Siliguri', sans-serif",
            }}
          >
            কাস্টমার তার মোবাইলের সাধারণ ক্যামেরা দিয়ে দোকানে টানানো কিউআর স্ক্যান করবে। কোনো অ্যাপ ডাউনলোড ছাড়াই চোখের
            পলকে পেজ খুলবে!
          </p>
        </div>

        {/* Step 2: Upload & Calculate (Enters at 8.5s) */}
        <div
          style={{
            transform: `translateY(${interpolate(step2, [0, 1], [50, 0])}px) scale(${step2})`,
            opacity: step2,
            backgroundColor: 'rgba(15, 23, 42, 0.85)',
            border: '2px solid rgba(244, 63, 94, 0.4)',
            borderRadius: 32,
            padding: 30,
            display: 'flex',
            flexDirection: 'column',
            alignItems: 'center',
            textAlign: 'center',
            backdropFilter: 'blur(20px)',
            boxShadow: '0 20px 40px rgba(0,0,0,0.6)',
          }}
        >
          <div
            style={{
              padding: '6px 18px',
              borderRadius: 9999,
              backgroundColor: '#f43f5e',
              color: '#ffffff',
              fontSize: 16,
              fontWeight: 900,
              fontFamily: "'Plus Jakarta Sans', sans-serif",
              marginBottom: 20,
            }}
          >
            STEP 02
          </div>
          {/* Upload SVG illustration */}
          <div
            style={{
              width: 170,
              height: 170,
              borderRadius: 24,
              backgroundColor: '#1e293b',
              border: '2px dashed #f43f5e',
              display: 'flex',
              flexDirection: 'column',
              alignItems: 'center',
              justifyContent: 'center',
              padding: 15,
            }}
          >
            <span style={{ fontSize: 40 }}>📄</span>
            <span style={{ fontSize: 13, fontWeight: 'bold', color: '#ffffff', marginTop: 6, fontFamily: 'sans-serif' }}>
              Assignment.pdf
            </span>
            <span style={{ fontSize: 12, color: '#10b981', fontWeight: 'bold', marginTop: 6, fontFamily: 'sans-serif' }}>
              ✓ {Math.round(countAnim)} পেজ • ৳{Math.round(billAnim)} অটো হিসাব
            </span>
          </div>
          <h3
            style={{
              fontSize: 26,
              fontWeight: 900,
              color: '#ffffff',
              marginTop: 20,
              fontFamily: "'Hind Siliguri', sans-serif",
            }}
          >
            ২. ফাইল আপলোড ও অটো-হিসাব
          </h3>
          <p
            style={{
              fontSize: 17,
              color: '#94a3b8',
              lineHeight: 1.5,
              marginTop: 10,
              fontFamily: "'Hind Siliguri', sans-serif",
            }}
          >
            পিডিএফ, ছবি বা এনআইডি আপলোড করুন। স্বয়ংক্রিয়ভাবে পেজ সংখ্যা ও রেট হিসাব হয়ে যাবে— দোকানদারকে হিসাব কষতে হবে
            না!
          </p>
        </div>

        {/* Step 3: Instant Print (Enters at 16.5s) */}
        <div
          style={{
            transform: `translateY(${interpolate(step3, [0, 1], [50, 0])}px) scale(${step3})`,
            opacity: step3,
            backgroundColor: 'rgba(15, 23, 42, 0.85)',
            border: '2px solid rgba(16, 185, 129, 0.4)',
            borderRadius: 32,
            padding: 30,
            display: 'flex',
            flexDirection: 'column',
            alignItems: 'center',
            textAlign: 'center',
            backdropFilter: 'blur(20px)',
            boxShadow: '0 20px 40px rgba(0,0,0,0.6)',
          }}
        >
          <div
            style={{
              padding: '6px 18px',
              borderRadius: 9999,
              backgroundColor: '#10b981',
              color: '#ffffff',
              fontSize: 16,
              fontWeight: 900,
              fontFamily: "'Plus Jakarta Sans', sans-serif",
              marginBottom: 20,
            }}
          >
            STEP 03
          </div>
          <div style={{ height: 170, display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
            <AnimatedPrinter size={200} isPrinting={true} />
          </div>
          <h3
            style={{
              fontSize: 26,
              fontWeight: 900,
              color: '#ffffff',
              marginTop: 20,
              fontFamily: "'Hind Siliguri', sans-serif",
            }}
          >
            ৩. প্রিন্টার থেকে অটো প্রিন্ট!
          </h3>
          <p
            style={{
              fontSize: 17,
              color: '#94a3b8',
              lineHeight: 1.5,
              marginTop: 10,
              fontFamily: "'Hind Siliguri', sans-serif",
            }}
          >
            বিকাশ/নগদ বা ক্যাশে পেমেন্ট কনফার্ম হওয়ামাত্রই দোকানের প্রিন্টার থেকে স্বয়ংক্রিয়ভাবে পেপার প্রিন্ট হয়ে বের হয়ে
            আসবে!
          </p>
        </div>
      </div>
    </div>
  );
};
