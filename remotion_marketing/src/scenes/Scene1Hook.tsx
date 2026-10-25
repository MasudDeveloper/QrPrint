import React from 'react';
import { interpolate, spring, useCurrentFrame, useVideoConfig } from 'remotion';
import { Background } from '../components/Background';
import { Badge, Title } from '../components/Typography';
import { AnimatedTangledCables } from '../components/SvgIcons';

export const Scene1Hook: React.FC = () => {
  const frame = useCurrentFrame();
  const { fps } = useVideoConfig();

  // Word-by-word timed animations matching voiceover:
  // 0s - 4.5s: "প্রতিদিন দোকানে কাস্টমারের ১ পাতার প্রিন্ট দিতে গিয়ে কি আপনি ক্লান্ত?"
  const titleAnim = spring({ frame: frame - 15, fps, config: { damping: 14 } });
  const punchAnim = spring({ frame: frame - 45, fps, config: { damping: 12, stiffness: 140 } });

  // 4.5s - 6.5s: "বারবার সেই একই ভোগান্তি—"
  const cablesAnim = spring({ frame: frame - 135, fps, config: { damping: 14 } });

  // 6.5s - 9.5s: "কখনো ব্লুটুথ কানেক্ট হচ্ছে না,"
  const dialog1 = spring({ frame: frame - 195, fps, config: { damping: 14, stiffness: 130 } });

  // 9.5s - 12.0s: "কখনো কেবল খোঁজাখুঁজি,"
  const dialog2 = spring({ frame: frame - 285, fps, config: { damping: 14, stiffness: 130 } });

  // 12.0s - 15.6s: "আর কখনো হোয়াটসঅ্যাপ নাম্বার আদান-প্রদান করতে করতেই চলে যাচ্ছে ঘণ্টার পর ঘণ্টা সময়!"
  const dialog3 = spring({ frame: frame - 360, fps, config: { damping: 14, stiffness: 130 } });

  // Shaking frustration effect
  const shake = Math.sin(frame * 0.4) * interpolate(punchAnim, [0, 1], [0, 4]);

  return (
    <div
      style={{
        position: 'absolute',
        inset: 0,
        display: 'flex',
        flexDirection: 'column',
        alignItems: 'center',
        justifyContent: 'center',
        padding: '0 80px',
        color: '#ffffff',
      }}
    >
      <Background variant="danger" />

      {/* Top Warning Badge */}
      <div style={{ marginBottom: 30 }}>
        <Badge text="দোকানদার ও কাস্টমারের প্রতিদিনের ভোগান্তি" icon="⚠️" color="rose" delay={10} />
      </div>

      {/* Main Hook Titles */}
      <div style={{ maxWidth: 1400, textAlign: 'center', transform: `translateY(${shake}px)` }}>
        <Title size={48} delay={15}>
          দোকানে কাস্টমারের মাত্র ১ পাতার প্রিন্ট দিতে গিয়ে...
        </Title>
        <div style={{ marginTop: 15 }}>
          <Title size={72} gradient="rose" delay={45}>
            আপনি কি চরম ক্লান্ত ও বিরক্ত?
          </Title>
        </div>
      </div>

      {/* Motion Visuals: Tangled Cable + 3 Chaos Problem Cards */}
      <div
        style={{
          display: 'flex',
          alignItems: 'center',
          justifyContent: 'center',
          gap: 40,
          marginTop: 60,
          width: '100%',
          maxWidth: 1400,
        }}
      >
        {/* Left: Animated Tangled Cables with Sparks */}
        <div
          style={{
            transform: `scale(${cablesAnim})`,
            opacity: cablesAnim,
            display: 'flex',
            flexDirection: 'column',
            alignItems: 'center',
            backgroundColor: 'rgba(15, 23, 42, 0.7)',
            border: '2px solid rgba(239, 68, 68, 0.3)',
            borderRadius: 32,
            padding: '30px 40px',
            backdropFilter: 'blur(16px)',
            boxShadow: '0 20px 40px rgba(239, 68, 68, 0.15)',
          }}
        >
          <AnimatedTangledCables size={240} />
          <span
            style={{
              marginTop: 15,
              fontSize: 20,
              fontWeight: 800,
              color: '#f87171',
              fontFamily: "'Hind Siliguri', sans-serif",
            }}
          >
            🚫 ক্যাবল খোঁজাখুঁজি ও ব্লুটুথ ফেইল
          </span>
        </div>

        {/* Right: Floating Frustration Dialogs Appearing with Voice Cues */}
        <div style={{ display: 'flex', flexDirection: 'column', gap: 20, flex: 1, maxWidth: 650 }}>
          {/* Dialog 1 (Voice: "কখনো ব্লুটুথ কানেক্ট হচ্ছে না") */}
          <div
            style={{
              transform: `translateX(${interpolate(dialog1, [0, 1], [60, 0])}px) scale(${dialog1})`,
              opacity: dialog1,
              backgroundColor: 'rgba(30, 41, 59, 0.9)',
              border: '1.5px solid rgba(239, 68, 68, 0.4)',
              borderRadius: 20,
              padding: '18px 25px',
              display: 'flex',
              alignItems: 'center',
              gap: 16,
              boxShadow: '0 10px 25px rgba(0,0,0,0.5)',
            }}
          >
            <span style={{ fontSize: 32 }}>📲</span>
            <div>
              <div style={{ fontSize: 22, fontWeight: 800, color: '#f87171', fontFamily: "'Hind Siliguri', sans-serif" }}>
                Bluetooth কানেকশন স্লো বা ফেইল্ড! ❌
              </div>
              <div style={{ fontSize: 16, color: '#cbd5e1', marginTop: 4, fontFamily: "'Hind Siliguri', sans-serif" }}>
                বারবার পেয়ারিং সমস্যা ও ফাইল সেন্ড আটকে থাকা
              </div>
            </div>
          </div>

          {/* Dialog 2 (Voice: "কখনো কেবল খোঁজাখুঁজি") */}
          <div
            style={{
              transform: `translateX(${interpolate(dialog2, [0, 1], [60, 0])}px) scale(${dialog2})`,
              opacity: dialog2,
              backgroundColor: 'rgba(30, 41, 59, 0.9)',
              border: '1.5px solid rgba(245, 158, 11, 0.4)',
              borderRadius: 20,
              padding: '18px 25px',
              display: 'flex',
              alignItems: 'center',
              gap: 16,
              boxShadow: '0 10px 25px rgba(0,0,0,0.5)',
            }}
          >
            <span style={{ fontSize: 32 }}>🔌</span>
            <div>
              <div style={{ fontSize: 22, fontWeight: 800, color: '#fcd34d', fontFamily: "'Hind Siliguri', sans-serif" }}>
                OTG ক্যাবল নেই? পেনড্রাইভে ভাইরাস!
              </div>
              <div style={{ fontSize: 16, color: '#cbd5e1', marginTop: 4, fontFamily: "'Hind Siliguri', sans-serif" }}>
                কম্পিউটার স্লো হওয়া ও কেবল ড্যামেজ হওয়ার আতঙ্ক
              </div>
            </div>
          </div>

          {/* Dialog 3 (Voice: "হোয়াটসঅ্যাপ নাম্বার আদান-প্রদান করতে চলে যাচ্ছে ঘণ্টার পর ঘণ্টা") */}
          <div
            style={{
              transform: `translateX(${interpolate(dialog3, [0, 1], [60, 0])}px) scale(${dialog3})`,
              opacity: dialog3,
              backgroundColor: 'rgba(30, 41, 59, 0.9)',
              border: '1.5px solid rgba(239, 68, 68, 0.4)',
              borderRadius: 20,
              padding: '18px 25px',
              display: 'flex',
              alignItems: 'center',
              gap: 16,
              boxShadow: '0 10px 25px rgba(0,0,0,0.5)',
            }}
          >
            <span style={{ fontSize: 32 }}>⏳</span>
            <div>
              <div style={{ fontSize: 22, fontWeight: 800, color: '#ffffff', fontFamily: "'Hind Siliguri', sans-serif" }}>
                "ভাইয়া, হোয়াটসঅ্যাপ নম্বরটা দেন..." ৫ মিনিট নষ্ট!
              </div>
              <div style={{ fontSize: 16, color: '#cbd5e1', marginTop: 4, fontFamily: "'Hind Siliguri', sans-serif" }}>
                দোকানে দীর্ঘ কাস্টমার সিরিয়াল ও ভিড়ের বিশৃঙ্খলা
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};
