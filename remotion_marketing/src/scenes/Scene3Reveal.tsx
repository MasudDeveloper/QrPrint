import React from 'react';
import { Img, interpolate, spring, staticFile, useCurrentFrame, useVideoConfig } from 'remotion';
import { Background } from '../components/Background';
import { Badge, Title } from '../components/Typography';
import { AnimatedQrScanner } from '../components/SvgIcons';

export const Scene3Reveal: React.FC = () => {
  const frame = useCurrentFrame();
  const { fps } = useVideoConfig();

  // 0s - 3.7s: "এই সব ঝামেলার দিন এবার শেষ!"
  const topBadge = spring({ frame: frame - 10, fps, config: { damping: 14 } });
  const titleSpring = spring({ frame: frame - 25, fps, config: { damping: 14 } });

  // 3.7s - 9.0s: "এসে গেছে PrintSheba— বাংলাদেশের সর্বপ্রথম সম্পূর্ণ স্বয়ংক্রিয় স্মার্ট কিউআর প্রিন্টিং প্ল্যাটফর্ম!"
  const qrScale = spring({ frame: frame - 105, fps, config: { damping: 12, stiffness: 100 } });
  const logoSpring = spring({ frame: frame - 135, fps, config: { damping: 14, stiffness: 120 } });

  // 9.0s - 14.0s: "কোনো ক্যাবল নেই, কোনো পেনড্রাইভ নেই, এমনকি কাস্টমারকে কোনো অ্যাপও ইনস্টল করতে হবে না"
  const pill1 = spring({ frame: frame - 270, fps, config: { damping: 14, stiffness: 140 } });
  const pill2 = spring({ frame: frame - 320, fps, config: { damping: 14, stiffness: 140 } });
  const pill3 = spring({ frame: frame - 370, fps, config: { damping: 14, stiffness: 140 } });

  // 14.0s - 18.2s: "শুধু একটি স্ক্যান— আর সাথে সাথেই ম্যাজিকের মতো প্রিন্ট!"
  const magicGlow = spring({ frame: frame - 420, fps, config: { damping: 12 } });

  // Floating ambient movement
  const floatY = Math.sin(frame * 0.05) * 8;

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
      <Background variant="emerald" />

      {/* Top Badge */}
      <div style={{ marginBottom: 20 }}>
        <Badge text="স্মার্ট প্রযুক্তির নতুন বিপ্লব" icon="✨" color="emerald" delay={10} />
      </div>

      <div style={{ textAlign: 'center', maxWidth: 1300 }}>
        <Title size={44} delay={25}>
          এই সব ঝামেলার দিন এবার শেষ!
        </Title>
      </div>

      {/* Centerpiece: Animated QR Scanner + PrintSheba Logo & Branding */}
      <div
        style={{
          display: 'flex',
          alignItems: 'center',
          justifyContent: 'center',
          gap: 60,
          marginTop: 40,
          width: '100%',
          maxWidth: 1300,
          transform: `translateY(${floatY}px)`,
        }}
      >
        {/* Left: Glowing Tech QR Scanner */}
        <div
          style={{
            transform: `scale(${qrScale})`,
            opacity: qrScale,
            filter: 'drop-shadow(0 0 40px rgba(16, 185, 129, 0.4))',
          }}
        >
          <AnimatedQrScanner size={320} />
        </div>

        {/* Right: PrintSheba Brand Info */}
        <div style={{ flex: 1, maxWidth: 700 }}>
          <div
            style={{
              transform: `scale(${logoSpring})`,
              opacity: logoSpring,
              display: 'flex',
              alignItems: 'center',
              gap: 20,
            }}
          >
            <Img
              src={staticFile('logo.svg')}
              style={{
                width: 90,
                height: 90,
                borderRadius: 24,
                boxShadow: '0 15px 35px rgba(244, 63, 94, 0.4)',
              }}
            />
            <div>
              <div
                style={{
                  fontSize: 60,
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
                  color: '#6ee7b7',
                  fontFamily: "'Hind Siliguri', sans-serif",
                  marginTop: 6,
                }}
              >
                প্রিন্টসেবা • স্মার্ট ক্লাউড প্রিন্টিং সল্যুশন
              </div>
            </div>
          </div>

          <p
            style={{
              fontSize: 24,
              color: '#cbd5e1',
              marginTop: 25,
              lineHeight: 1.5,
              fontFamily: "'Hind Siliguri', sans-serif",
            }}
          >
            বাংলাদেশের সর্বপ্রথম সম্পূর্ণ স্বয়ংক্রিয় <span style={{ color: '#ffffff', fontWeight: 800 }}>কিউআর কোড প্রিন্টিং প্ল্যাটফর্ম</span>।
            দোকানে টানানো থাকবে আপনার দোকানের কিউআর পোস্টার!
          </p>

          {/* 3 Game-Changing Feature Badges */}
          <div style={{ display: 'flex', flexWrap: 'wrap', gap: 15, marginTop: 30 }}>
            {/* Pill 1 */}
            <div
              style={{
                transform: `scale(${pill1})`,
                opacity: pill1,
                padding: '12px 22px',
                borderRadius: 9999,
                backgroundColor: 'rgba(244, 63, 94, 0.15)',
                border: '1.5px solid rgba(244, 63, 94, 0.4)',
                color: '#fca5a5',
                fontSize: 18,
                fontWeight: 800,
                fontFamily: "'Hind Siliguri', sans-serif",
              }}
            >
              🚫 কোনো ক্যাবল নেই
            </div>

            {/* Pill 2 */}
            <div
              style={{
                transform: `scale(${pill2})`,
                opacity: pill2,
                padding: '12px 22px',
                borderRadius: 9999,
                backgroundColor: 'rgba(245, 158, 11, 0.15)',
                border: '1.5px solid rgba(245, 158, 11, 0.4)',
                color: '#fcd34d',
                fontSize: 18,
                fontWeight: 800,
                fontFamily: "'Hind Siliguri', sans-serif",
              }}
            >
              🚫 কোনো পেনড্রাইভ নেই
            </div>

            {/* Pill 3 */}
            <div
              style={{
                transform: `scale(${pill3})`,
                opacity: pill3,
                padding: '12px 22px',
                borderRadius: 9999,
                backgroundColor: 'rgba(16, 185, 129, 0.15)',
                border: '1.5px solid rgba(16, 185, 129, 0.4)',
                color: '#6ee7b7',
                fontSize: 18,
                fontWeight: 800,
                fontFamily: "'Hind Siliguri', sans-serif",
              }}
            >
              🚫 কোনো অ্যাপ ইনস্টল ছাড়াই!
            </div>
          </div>

          {/* Golden Magic Slogan (Voice: "শুধু একটি স্ক্যান— আর সাথে সাথেই ম্যাজিকের মতো প্রিন্ট!") */}
          <div
            style={{
              transform: `scale(${magicGlow})`,
              opacity: magicGlow,
              marginTop: 25,
              padding: '10px 20px',
              borderRadius: 16,
              background: 'linear-gradient(90deg, rgba(16,185,129,0.2), rgba(245,158,11,0.2))',
              border: '1.5px solid #34d399',
              color: '#fcd34d',
              fontSize: 18,
              fontWeight: 900,
              fontFamily: "'Hind Siliguri', sans-serif",
              display: 'inline-block',
            }}
          >
            ⚡ শুধু একটি স্ক্যান — আর সাথে সাথেই ম্যাজিকের মতো প্রিন্ট!
          </div>
        </div>
      </div>
    </div>
  );
};
