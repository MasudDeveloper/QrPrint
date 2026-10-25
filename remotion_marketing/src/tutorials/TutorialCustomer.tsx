import React from 'react';
import {
  AbsoluteFill,
  interpolate,
  spring,
  useCurrentFrame,
  useVideoConfig,
} from 'remotion';
import { MockCustomerMobileScreen } from '../components/ui_mockups/MockCustomerMobileScreen';
import { MockCursor } from '../components/ui_mockups/MockCursor';
import { MockQrPosterScreen } from '../components/ui_mockups/MockQrPosterScreen';

export const TutorialCustomer: React.FC = () => {
  const frame = useCurrentFrame();
  const { fps } = useVideoConfig();

  // Step 1: 0 - 360 (0 - 12s) -> Scan QR Poster
  // Step 2: 360 - 810 (12 - 27s) -> File Select & Auto-Page Detect
  // Step 3: 810 - 1260 (27 - 42s) -> Color, Copies, Duplex & Live Bill
  // Step 4: 1260 - 1800 (42 - 60s) -> bKash/Cash & Auto Print Out

  // Current active step number
  let activeStep = 1;
  let stepTitle = 'ধাপ ১: কাউন্টারে টানানো QR কোড মোবাইলে স্ক্যান করুন';
  let subtitle = 'কোনো অ্যাপ ডাউনলোড করতে হবে না— ক্যামেরা দিয়ে স্ক্যান করলেই পেজ ওপেন হবে!';

  if (frame >= 360 && frame < 810) {
    activeStep = 2;
    stepTitle = 'ধাপ ২: ফাইল নির্বাচন ও অটো-পেজ কাউন্ট';
    subtitle = 'পিডিএফ বা যেকোনো ফাইল আপলোড করুন, সিস্টেম স্বয়ংক্রিয়ভাবে পেজ সংখ্যা গুনে নেবে।';
  } else if (frame >= 810 && frame < 1260) {
    activeStep = 3;
    stepTitle = 'ধাপ ৩: কালার মোড, কপি ও লাইভ বিল ক্যালকুলেটর';
    subtitle = 'সাদাকালো বা রঙিন পছন্দ করুন, কপি সংখ্যা দিন— লাইভ বিল তাৎক্ষণিক তৈরি হবে।';
  } else if (frame >= 1260) {
    activeStep = 4;
    stepTitle = 'ধাপ ৪: বিকাশ/নগদ/ক্যাশ ও স্বয়ংক্রিয় অটো-প্রিন্ট';
    subtitle = 'পেমেন্ট সম্পন্ন হওয়ামাত্রই দোকানের প্রিন্টার থেকে স্বয়ংক্রিয়ভাবে কাগজ বের হবে!';
  }

  // Cursor animation simulation
  // Frame 450 - 540: cursor moves to file upload area
  // Frame 900 - 990: cursor clicks color mode
  // Frame 1020 - 1080: cursor clicks duplex
  // Frame 1320 - 1400: cursor clicks bKash
  // Frame 1440 - 1500: cursor clicks print button
  let cursorX = 960;
  let cursorY = 540;
  let isClicking = false;
  let cursorLabel = '';
  let showCursor = false;

  if (frame >= 380 && frame < 750) {
    showCursor = true;
    cursorLabel = 'ফাইল নির্বাচন করুন';
    cursorX = interpolate(frame, [380, 480], [1200, 960], { extrapolateRight: 'clamp' });
    cursorY = interpolate(frame, [380, 480], [700, 480], { extrapolateRight: 'clamp' });
    if (frame >= 480 && frame < 520) isClicking = true;
  } else if (frame >= 820 && frame < 1000) {
    showCursor = true;
    cursorLabel = '🎨 কালার প্রিন্ট';
    cursorX = interpolate(frame, [820, 900], [960, 1020], { extrapolateRight: 'clamp' });
    cursorY = interpolate(frame, [820, 900], [480, 460], { extrapolateRight: 'clamp' });
    if (frame >= 900 && frame < 940) isClicking = true;
  } else if (frame >= 1000 && frame < 1180) {
    showCursor = true;
    cursorLabel = '📄 এপিঠ-ওপিঠ (ছাড়)';
    cursorX = interpolate(frame, [1000, 1060], [1020, 960], { extrapolateRight: 'clamp' });
    cursorY = interpolate(frame, [1000, 1060], [460, 560], { extrapolateRight: 'clamp' });
    if (frame >= 1060 && frame < 1100) isClicking = true;
  } else if (frame >= 1280 && frame < 1550) {
    showCursor = true;
    cursorLabel = frame < 1440 ? 'bKash সিলেক্ট' : '⚡ প্রিন্ট করুন';
    cursorX = interpolate(frame, [1280, 1340, 1420, 1470], [960, 890, 890, 960], { extrapolateRight: 'clamp' });
    cursorY = interpolate(frame, [1280, 1340, 1420, 1470], [560, 680, 680, 780], { extrapolateRight: 'clamp' });
    if ((frame >= 1340 && frame < 1370) || (frame >= 1470 && frame < 1510)) isClicking = true;
  }

  // Dynamic mobile screen states depending on frame timeline
  const hasFile = frame >= 500;
  const colorMode = frame >= 920 ? 'color' : 'bw';
  const copies = frame >= 1020 ? 2 : 1;
  const isDuplex = frame >= 1080;
  const totalBill = frame < 920 ? 15 : frame < 1020 ? 50 : isDuplex ? 90 : 100;
  const paymentMethod = 'bkash';
  const isSubmitted = frame >= 1490;

  // Camera scale and position
  const mobileScale = spring({
    frame: frame - 300,
    fps,
    config: { damping: 14, mass: 0.8 },
  });

  return (
    <AbsoluteFill
      style={{
        backgroundColor: '#030712',
        color: '#ffffff',
        fontFamily: "'Hind Siliguri', sans-serif",
        overflow: 'hidden',
      }}
    >
      {/* Background Ambient Glow & Grid */}
      <div
        style={{
          position: 'absolute',
          top: -200,
          left: '20%',
          width: 800,
          height: 600,
          borderRadius: '50%',
          backgroundImage: 'radial-gradient(circle, rgba(225,29,72,0.18) 0%, rgba(3,7,18,0) 70%)',
          pointerEvents: 'none',
        }}
      />
      <div
        style={{
          position: 'absolute',
          bottom: -150,
          right: '15%',
          width: 700,
          height: 500,
          borderRadius: '50%',
          backgroundImage: 'radial-gradient(circle, rgba(16,185,129,0.15) 0%, rgba(3,7,18,0) 70%)',
          pointerEvents: 'none',
        }}
      />

      {/* Top Header Bar */}
      <div
        style={{
          position: 'absolute',
          top: 0,
          left: 0,
          right: 0,
          height: 90,
          backgroundColor: 'rgba(15,23,42,0.85)',
          backdropFilter: 'blur(16px)',
          borderBottom: '1px solid rgba(255,255,255,0.1)',
          display: 'flex',
          alignItems: 'center',
          justifyContent: 'space-between',
          padding: '0 60px',
          zIndex: 40,
        }}
      >
        {/* Brand Logo & Title */}
        <div style={{ display: 'flex', alignItems: 'center', gap: 16 }}>
          <div
            style={{
              width: 48,
              height: 48,
              borderRadius: 14,
              backgroundColor: '#e11d48',
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
              color: '#fff',
              fontSize: 24,
              fontWeight: 900,
              boxShadow: '0 8px 20px rgba(225,29,72,0.4)',
            }}
          >
            PS
          </div>
          <div>
            <div style={{ display: 'flex', alignItems: 'center', gap: 10 }}>
              <span style={{ fontSize: 22, fontWeight: 900, letterSpacing: '0.02em' }}>
                PrintSheba <span style={{ color: '#fb7185' }}>টিউটোরিয়াল</span>
              </span>
              <span
                style={{
                  padding: '3px 10px',
                  borderRadius: 8,
                  backgroundColor: 'rgba(16,185,129,0.2)',
                  border: '1px solid #10b981',
                  color: '#6ee7b7',
                  fontSize: 12,
                  fontWeight: 800,
                }}
              >
                ইউজার / কাস্টমার সেলফ-সার্ভিস গাইড
              </span>
            </div>
            <span style={{ fontSize: 13, color: '#94a3b8' }}>
              মোবাইল দিয়ে কিউআর স্ক্যান করে দ্রুত ও নিরাপদ প্রিন্ট
            </span>
          </div>
        </div>

        {/* 4 Steps Visual Flow Bar */}
        <div style={{ display: 'flex', alignItems: 'center', gap: 12 }}>
          {[
            { num: 1, label: 'কিউআর স্ক্যান' },
            { num: 2, label: 'ফাইল আপলোড' },
            { num: 3, label: 'বিল ও অপশন' },
            { num: 4, label: 'অটো প্রিন্ট' },
          ].map((item) => {
            const isCurrent = activeStep === item.num;
            const isDone = activeStep > item.num;
            return (
              <div
                key={item.num}
                style={{
                  display: 'flex',
                  alignItems: 'center',
                  gap: 8,
                  padding: '6px 14px',
                  borderRadius: 12,
                  backgroundColor: isCurrent ? 'rgba(225,29,72,0.2)' : isDone ? 'rgba(16,185,129,0.15)' : '#0f172a',
                  border: isCurrent ? '1.5px solid #e11d48' : isDone ? '1px solid #10b981' : '1px solid #1e293b',
                  color: isCurrent ? '#fb7185' : isDone ? '#34d399' : '#64748b',
                  fontSize: 13,
                  fontWeight: 800,
                }}
              >
                <span
                  style={{
                    width: 22,
                    height: 22,
                    borderRadius: '50%',
                    backgroundColor: isCurrent ? '#e11d48' : isDone ? '#10b981' : '#1e293b',
                    color: '#ffffff',
                    display: 'flex',
                    alignItems: 'center',
                    justifyContent: 'center',
                    fontSize: 11,
                    fontWeight: 900,
                  }}
                >
                  {isDone ? '✓' : item.num}
                </span>
                <span>{item.label}</span>
              </div>
            );
          })}
        </div>
      </div>

      {/* Main Visual Stage */}
      <div
        style={{
          position: 'absolute',
          top: 90,
          bottom: 110,
          left: 0,
          right: 0,
          display: 'flex',
          alignItems: 'center',
          justifyContent: 'center',
        }}
      >
        {/* SCENE 1: Showing Counter QR poster being scanned (0 - 360 frames) */}
        {frame < 360 && (
          <div
            style={{
              display: 'flex',
              alignItems: 'center',
              gap: 80,
              transform: `scale(${interpolate(frame, [0, 40], [0.9, 1], { extrapolateRight: 'clamp' })})`,
              opacity: interpolate(frame, [320, 360], [1, 0], { extrapolateRight: 'clamp' }),
            }}
          >
            {/* Left: Poster on Shop Counter */}
            <div style={{ transform: 'rotate(-2deg)' }}>
              <MockQrPosterScreen shopName="গ্রিন সাইবার ক্যাফে ও কম্পিউটার্স" />
            </div>

            {/* Right: Phone Scanning Beam Animation */}
            <div
              style={{
                width: 320,
                padding: '24px',
                borderRadius: 24,
                backgroundColor: '#0f172a',
                border: '2px solid #10b981',
                boxShadow: '0 20px 50px rgba(16,185,129,0.3)',
                display: 'flex',
                flexDirection: 'column',
                alignItems: 'center',
                gap: 16,
                textAlign: 'center',
              }}
            >
              <div style={{ fontSize: 48 }}>📱</div>
              <div style={{ fontSize: 18, fontWeight: 900, color: '#34d399' }}>
                মোবাইল ক্যামেরা দিয়ে স্ক্যান করুন
              </div>
              <p style={{ fontSize: 13, color: '#94a3b8', lineHeight: 1.5 }}>
                কোনো অ্যাপের প্রয়োজন নেই। যেকোনো স্মার্টফোনের ক্যামেরা খুললেই লিংক ভেসে উঠবে!
              </p>
              <div
                style={{
                  padding: '8px 16px',
                  borderRadius: 12,
                  backgroundColor: '#10b981',
                  color: '#000000',
                  fontSize: 12,
                  fontWeight: 900,
                }}
              >
                🔗 printsheba.com/c/green-cyber
              </div>
            </div>
          </div>
        )}

        {/* SCENE 2, 3, 4: Interactive Mobile UI (360 - 1800 frames) */}
        {frame >= 320 && (
          <div
            style={{
              display: 'flex',
              alignItems: 'center',
              gap: 80,
              opacity: interpolate(frame, [320, 360], [0, 1], { extrapolateRight: 'clamp' }),
              transform: `scale(${Math.min(1, Math.max(0.7, mobileScale))})`,
            }}
          >
            {/* Left Phone Screen Mockup */}
            <MockCustomerMobileScreen
              hasFile={hasFile}
              fileName="Proposal_Final.pdf"
              totalPages={5}
              colorMode={colorMode}
              copies={copies}
              isDuplex={isDuplex}
              totalBill={totalBill}
              paymentMethod={paymentMethod}
              isSubmitted={isSubmitted}
              jobToken="PS-89412"
            />

            {/* Right Feature Callouts & Highlights */}
            <div style={{ width: 440, display: 'flex', flexDirection: 'column', gap: 20 }}>
              {/* Feature card 1 */}
              <div
                style={{
                  padding: '20px 24px',
                  borderRadius: 20,
                  backgroundColor: activeStep === 2 ? 'rgba(225,29,72,0.15)' : '#0f172a',
                  border: activeStep === 2 ? '2px solid #e11d48' : '1.5px solid #1e293b',
                  display: 'flex',
                  gap: 16,
                  alignItems: 'center',
                  transition: 'all 0.3s ease',
                }}
              >
                <div style={{ fontSize: 32 }}>📄</div>
                <div>
                  <h4 style={{ fontSize: 16, fontWeight: 900, margin: 0, color: activeStep === 2 ? '#fb7185' : '#ffffff' }}>
                    অটোমেটিক পেজ কাউন্টার
                  </h4>
                  <p style={{ fontSize: 12, color: '#94a3b8', margin: '4px 0 0 0', lineHeight: 1.4 }}>
                    পিডিএফ বা যেকোনো ফাইল আপলোড করলেই পেজ সংখ্যা স্বয়ংক্রিয়ভাবে গুনে সঠিক হিসাব করে।
                  </p>
                </div>
              </div>

              {/* Feature card 2 */}
              <div
                style={{
                  padding: '20px 24px',
                  borderRadius: 20,
                  backgroundColor: activeStep === 3 ? 'rgba(236,72,153,0.15)' : '#0f172a',
                  border: activeStep === 3 ? '2px solid #ec4899' : '1.5px solid #1e293b',
                  display: 'flex',
                  gap: 16,
                  alignItems: 'center',
                  transition: 'all 0.3s ease',
                }}
              >
                <div style={{ fontSize: 32 }}>🎨</div>
                <div>
                  <h4 style={{ fontSize: 16, fontWeight: 900, margin: 0, color: activeStep === 3 ? '#f472b6' : '#ffffff' }}>
                    কালার ও এপিঠ-ওপিঠ (Duplex)
                  </h4>
                  <p style={{ fontSize: 12, color: '#94a3b8', margin: '4px 0 0 0', lineHeight: 1.4 }}>
                    সাদাকালো বা রঙিন সিলেক্ট করুন। বোথ সাইড প্রিন্ট নিলে স্বয়ংক্রিয় ডিসকাউন্ট রেট কার্যকর হয়।
                  </p>
                </div>
              </div>

              {/* Feature card 3 */}
              <div
                style={{
                  padding: '20px 24px',
                  borderRadius: 20,
                  backgroundColor: activeStep === 4 ? 'rgba(16,185,129,0.15)' : '#0f172a',
                  border: activeStep === 4 ? '2px solid #10b981' : '1.5px solid #1e293b',
                  display: 'flex',
                  gap: 16,
                  alignItems: 'center',
                  transition: 'all 0.3s ease',
                }}
              >
                <div style={{ fontSize: 32 }}>⚡</div>
                <div>
                  <h4 style={{ fontSize: 16, fontWeight: 900, margin: 0, color: activeStep === 4 ? '#6ee7b7' : '#ffffff' }}>
                    ১০০% স্বয়ংক্রিয় ও নিরাপদ প্রিন্টিং
                  </h4>
                  <p style={{ fontSize: 12, color: '#94a3b8', margin: '4px 0 0 0', lineHeight: 1.4 }}>
                    পেমেন্ট কনফার্ম হওয়ামাত্রই দোকানের প্রিন্টার থেকে সরাসরি প্রিন্ট শুরু হবে। কোনো ফাইল কম্পিউটারে সেভ থাকে না।
                  </p>
                </div>
              </div>
            </div>
          </div>
        )}

        {/* Animated Mouse/Touch Pointer */}
        {showCursor && (
          <MockCursor
            x={cursorX}
            y={cursorY}
            isClicking={isClicking}
            label={cursorLabel}
          />
        )}
      </div>

      {/* Bottom Subtitle / Voiceover Banner */}
      <div
        style={{
          position: 'absolute',
          bottom: 0,
          left: 0,
          right: 0,
          height: 100,
          backgroundColor: 'rgba(15,23,42,0.92)',
          backdropFilter: 'blur(16px)',
          borderTop: '1px solid rgba(255,255,255,0.1)',
          display: 'flex',
          alignItems: 'center',
          justifyContent: 'space-between',
          padding: '0 60px',
          zIndex: 40,
        }}
      >
        <div style={{ display: 'flex', alignItems: 'center', gap: 20 }}>
          <div
            style={{
              padding: '8px 18px',
              borderRadius: 14,
              backgroundColor: '#e11d48',
              color: '#ffffff',
              fontSize: 14,
              fontWeight: 900,
              boxShadow: '0 6px 16px rgba(225,29,72,0.3)',
            }}
          >
            {stepTitle}
          </div>
          <span style={{ fontSize: 16, color: '#e2e8f0', fontWeight: 600 }}>
            {subtitle}
          </span>
        </div>

        {/* Brand Signoff */}
        <div style={{ fontSize: 13, color: '#64748b', fontWeight: 700 }}>
          PrintSheba.com • স্মার্ট ফটোকপি ও প্রিন্টিং সেবা
        </div>
      </div>
    </AbsoluteFill>
  );
};
