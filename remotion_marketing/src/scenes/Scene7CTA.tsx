import React from 'react';
import { Img, interpolate, spring, staticFile, useCurrentFrame, useVideoConfig } from 'remotion';
import { Background } from '../components/Background';
import { Badge, Title } from '../components/Typography';

export const Scene7CTA: React.FC = () => {
  const frame = useCurrentFrame();
  const { fps } = useVideoConfig();

  // 0s - 7.0s: "স্মার্ট বাংলাদেশের সাথে আপনার ফটোকপি ও প্রিন্টিং ব্যবসাকে আজই করে তুলুন সম্পূর্ণ স্মার্ট ও ডিজিটাল!"
  const logoAnim = spring({ frame: frame - 15, fps, config: { damping: 14, stiffness: 120 } });
  const titleAnim = spring({ frame: frame - 40, fps, config: { damping: 14 } });

  // 7.0s - 12.2s: "কোনো দীর্ঘমেয়াদী চুক্তি নেই, মাত্র ৫ মিনিটে শুরু করুন সম্পূর্ণ ফ্রি ট্রায়াল"
  const trialAnim = spring({ frame: frame - 210, fps, config: { damping: 12, stiffness: 110 } });

  // 12.2s - 17.2s: "এখনই ভিজিট করুন: printsheba.com অথবা কল করুন স্ক্রিনে দেওয়া নম্বরে"
  const webAnim = spring({ frame: frame - 365, fps, config: { damping: 12, stiffness: 120 } });

  // 17.2s - 20.33s: "প্রিন্টসেবা — প্রিন্টিংয়ের নতুন বিপ্লব!"
  const sloganAnim = spring({ frame: frame - 510, fps, config: { damping: 12 } });

  const pulse = Math.sin(frame * 0.1) * 0.04 + 1;

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
      <Background variant="cyber" />

      {/* Top Badge */}
      <div style={{ marginBottom: 20 }}>
        <Badge text="দেরি না করে আজই যুক্ত হোন" icon="🎉" color="rose" delay={10} />
      </div>

      {/* PrintSheba Big Centered Logo */}
      <div
        style={{
          transform: `scale(${logoAnim})`,
          opacity: logoAnim,
          display: 'flex',
          alignItems: 'center',
          gap: 24,
          marginBottom: 20,
        }}
      >
        <Img
          src={staticFile('logo.svg')}
          style={{
            width: 100,
            height: 100,
            borderRadius: 28,
            boxShadow: '0 20px 45px rgba(244, 63, 94, 0.5)',
          }}
        />
        <div>
          <div
            style={{
              fontSize: 68,
              fontWeight: 900,
              letterSpacing: '-0.03em',
              fontFamily: "'Plus Jakarta Sans', sans-serif",
              lineHeight: 1,
            }}
          >
            Print<span style={{ color: '#f43f5e' }}>Sheba</span>
          </div>
          <div
            style={{
              fontSize: 22,
              fontWeight: 700,
              color: '#fda4af',
              fontFamily: "'Hind Siliguri', sans-serif",
              marginTop: 6,
            }}
          >
            স্মার্ট কিউআর প্রিন্টিং প্ল্যাটফর্ম
          </div>
        </div>
      </div>

      {/* Main Closing Heading */}
      <div style={{ textAlign: 'center', maxWidth: 1300 }}>
        <Title size={52} delay={40}>
          আপনার ফটোকপি ও প্রিন্টিং ব্যবসাকে আজই করুন স্মার্ট ও ডিজিটাল!
        </Title>
        <p
          style={{
            fontSize: 24,
            color: '#cbd5e1',
            marginTop: 15,
            fontFamily: "'Hind Siliguri', sans-serif",
          }}
        >
          কোনো অগ্রিম ফি নেই • কোনো জটিল সেটআপ নেই • মাত্র ৫ মিনিটে আপনার দোকানে ইনস্ট্যান্ট চালু!
        </p>
      </div>

      {/* High-Converting CTA Box */}
      <div
        style={{
          display: 'flex',
          alignItems: 'center',
          gap: 30,
          marginTop: 45,
        }}
      >
        {/* Main Website URL Button (Voice: "এখনই ভিজিট করুন printsheba.com") */}
        <div
          style={{
            transform: `scale(${webAnim * pulse})`,
            opacity: webAnim,
            padding: '20px 45px',
            borderRadius: 24,
            backgroundImage: 'linear-gradient(135deg, #f43f5e 0%, #e11d48 100%)',
            boxShadow: '0 20px 50px rgba(244, 63, 94, 0.45)',
            display: 'flex',
            alignItems: 'center',
            gap: 15,
          }}
        >
          <span style={{ fontSize: 32 }}>🌐</span>
          <div>
            <span style={{ fontSize: 13, textTransform: 'uppercase', fontWeight: 800, color: '#fecdd3', display: 'block', fontFamily: 'sans-serif' }}>
              VISIT WEBSITE
            </span>
            <span style={{ fontSize: 30, fontWeight: 900, color: '#ffffff', fontFamily: "'Plus Jakarta Sans', sans-serif" }}>
              printsheba.com
            </span>
          </div>
        </div>

        {/* Free Trial Pill (Voice: "মাত্র ৫ মিনিটে শুরু করুন সম্পূর্ণ ফ্রি ট্রায়াল") */}
        <div
          style={{
            transform: `scale(${trialAnim})`,
            opacity: trialAnim,
            padding: '20px 35px',
            borderRadius: 24,
            backgroundColor: 'rgba(15, 23, 42, 0.9)',
            border: '2px solid rgba(16, 185, 129, 0.5)',
            boxShadow: '0 15px 35px rgba(0,0,0,0.5)',
            display: 'flex',
            alignItems: 'center',
            gap: 15,
          }}
        >
          <span style={{ fontSize: 32 }}>⚡</span>
          <div>
            <span style={{ fontSize: 13, textTransform: 'uppercase', fontWeight: 800, color: '#6ee7b7', display: 'block', fontFamily: 'sans-serif' }}>
              INSTANT ACCESS
            </span>
            <span style={{ fontSize: 24, fontWeight: 900, color: '#ffffff', fontFamily: "'Hind Siliguri', sans-serif" }}>
              ৫ মিনিটে ফ্রি ট্রায়াল শুরু করুন
            </span>
          </div>
        </div>
      </div>

      {/* Footer Tagline (Voice: "প্রিন্টসেবা — প্রিন্টিংয়ের নতুন বিপ্লব!") */}
      <div
        style={{
          transform: `scale(${sloganAnim})`,
          opacity: sloganAnim,
          marginTop: 45,
          padding: '12px 30px',
          borderRadius: 9999,
          background: 'rgba(15, 23, 42, 0.7)',
          border: '1.5px solid rgba(245, 158, 11, 0.4)',
          color: '#fcd34d',
          fontSize: 24,
          fontWeight: 900,
          letterSpacing: '0.03em',
          fontFamily: "'Hind Siliguri', sans-serif",
          boxShadow: '0 10px 25px rgba(245, 158, 11, 0.2)',
        }}
      >
        ✨ প্রিন্টসেবা — প্রিন্টিংয়ের নতুন বিপ্লব! ✨
      </div>
    </div>
  );
};
