import React from 'react';
import { interpolate, spring, useCurrentFrame, useVideoConfig } from 'remotion';
import { Background } from '../components/Background';
import { Badge, Title } from '../components/Typography';

export const Scene6Transformation: React.FC = () => {
  const frame = useCurrentFrame();
  const { fps } = useVideoConfig();

  // 0s - 8.0s: "আগে যেখানে একজন কাস্টমারের পেছনে ১০ মিনিট নষ্ট হতো, এখন একই সাথে ১০ জন কাস্টমার নিজে নিজে প্রিন্ট দিচ্ছে"
  const leftCard = spring({ frame: frame - 20, fps, config: { damping: 14 } });
  const rightCard = spring({ frame: frame - 90, fps, config: { damping: 14 } });

  // 8.0s - 13.0s: "দোকানে কোনো দীর্ঘ লাইন নেই, কোনো কাস্টমার অসন্তুষ্ট হয়ে ফিরে যাচ্ছে না"
  const bulletGlow = spring({ frame: frame - 240, fps, config: { damping: 14 } });

  // 13.0s - 18.0s: "সময় বাঁচবে ৮০ ভাগ, আর আপনার দোকানের আয় বাড়বে দ্বিগুণেরও বেশি!"
  const barProgress = interpolate(frame, [380, 500], [0, 1], {
    extrapolateLeft: 'clamp',
    extrapolateRight: 'clamp',
  });

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
      <Background variant="gold" />

      {/* Top Badge */}
      <div style={{ marginBottom: 15 }}>
        <Badge text="ব্যবসার অভাবনীয় রূপান্তর ও প্রবৃদ্ধি" icon="📈" color="emerald" delay={5} />
      </div>

      <Title size={44} delay={10}>
        আগে বনাম এখন: আপনার দোকানের আসল পার্থক্য!
      </Title>
      <div style={{ marginTop: 8 }}>
        <Title size={56} gradient="emerald" delay={25}>
          ৮০% সময় সাশ্রয় • ৩ গুণ বেশি দৈনিক আয়
        </Title>
      </div>

      {/* Before vs After Side-by-Side Comparison */}
      <div
        style={{
          display: 'grid',
          gridTemplateColumns: 'repeat(2, 1fr)',
          gap: 40,
          marginTop: 45,
          width: '100%',
          maxWidth: 1400,
        }}
      >
        {/* Left: Traditional Way (আগে) */}
        <div
          style={{
            transform: `scale(${leftCard})`,
            opacity: leftCard,
            backgroundColor: 'rgba(20, 20, 30, 0.85)',
            border: '2px solid rgba(239, 68, 68, 0.35)',
            borderRadius: 30,
            padding: 35,
            backdropFilter: 'blur(16px)',
            boxShadow: '0 15px 35px rgba(0,0,0,0.5)',
          }}
        >
          <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between' }}>
            <span
              style={{
                fontSize: 16,
                fontWeight: 800,
                color: '#f87171',
                backgroundColor: 'rgba(239, 68, 68, 0.15)',
                padding: '6px 16px',
                borderRadius: 9999,
                fontFamily: "'Hind Siliguri', sans-serif",
              }}
            >
              ❌ আগের সনাতন পদ্ধতি
            </span>
            <span style={{ fontSize: 28 }}>🐢</span>
          </div>

          <h3
            style={{
              fontSize: 26,
              fontWeight: 900,
              color: '#ffffff',
              margin: '20px 0 15px 0',
              fontFamily: "'Hind Siliguri', sans-serif",
            }}
          >
            অতিরিক্ত পরিশ্রম ও সীমিত আয়
          </h3>

          <ul
            style={{
              listStyle: 'none',
              padding: 0,
              margin: 0,
              display: 'flex',
              flexDirection: 'column',
              gap: 14,
              fontSize: 18,
              color: '#94a3b8',
              fontFamily: "'Hind Siliguri', sans-serif",
            }}
          >
            <li>🔴 প্রতি কাস্টমারে ১০ মিনিট অপেক্ষা ও ভিড়</li>
            <li>🔴 কেবল, ব্লুটুথ আর পেনড্রাইভের ভাইরাসের ভয়</li>
            <li>🔴 কাস্টমার দাঁড়িয়ে বিরক্ত হয়ে ফিরে যাওয়া</li>
            <li>🔴 দিনশেষে ক্যাশ আর হিসাব মেলাতে মাথাব্যথা</li>
          </ul>
        </div>

        {/* Right: With PrintSheba (এখন) */}
        <div
          style={{
            transform: `scale(${rightCard})`,
            opacity: rightCard,
            backgroundColor: 'rgba(15, 30, 25, 0.9)',
            border: '2.5px solid rgba(16, 185, 129, 0.6)',
            borderRadius: 30,
            padding: 35,
            backdropFilter: 'blur(16px)',
            boxShadow: '0 20px 45px rgba(16, 185, 129, 0.25)',
          }}
        >
          <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between' }}>
            <span
              style={{
                fontSize: 16,
                fontWeight: 800,
                color: '#6ee7b7',
                backgroundColor: 'rgba(16, 185, 129, 0.2)',
                padding: '6px 16px',
                borderRadius: 9999,
                fontFamily: "'Hind Siliguri', sans-serif",
              }}
            >
              ⚡ PrintSheba স্মার্ট পদ্ধতি
            </span>
            <span style={{ fontSize: 28 }}>🚀</span>
          </div>

          <h3
            style={{
              fontSize: 26,
              fontWeight: 900,
              color: '#ffffff',
              margin: '20px 0 15px 0',
              fontFamily: "'Hind Siliguri', sans-serif",
            }}
          >
            স্মার্ট সেলফ-সার্ভিস ও ৩ গুণ মুনাফা!
          </h3>

          <ul
            style={{
              listStyle: 'none',
              padding: 0,
              margin: 0,
              display: 'flex',
              flexDirection: 'column',
              gap: 14,
              fontSize: 18,
              color: '#d1fae5',
              fontFamily: "'Hind Siliguri', sans-serif",
              fontWeight: 600,
            }}
          >
            <li>🟢 একই সাথে ১০ জন কাস্টমার নিজে নিজেই প্রিন্ট দেয়</li>
            <li>🟢 ০ সেকেন্ড ফাইল শেয়ারিং সময় — নো ক্যাবল, নো পিসি অ্যাক্সেস!</li>
            <li>🟢 শতভাগ নিরাপদ জিরো-রিটেনশন প্রাইভেসি</li>
            <li>🟢 স্বয়ংক্রিয় পেজ হিসাব ও বিকাশ/নগদ ডিজিটাল সেলস</li>
          </ul>

          {/* Animated Profit Bar */}
          <div style={{ marginTop: 25 }}>
            <div style={{ display: 'flex', justifyContent: 'space-between', fontSize: 14, fontWeight: 'bold' }}>
              <span style={{ color: '#6ee7b7', fontFamily: "'Hind Siliguri', sans-serif" }}>দোকানের মাসিক আয় বৃদ্ধি</span>
              <span style={{ color: '#ffffff', fontFamily: 'sans-serif' }}>+{Math.round(barProgress * 250)}%</span>
            </div>
            <div
              style={{
                height: 12,
                borderRadius: 6,
                backgroundColor: '#064e3b',
                marginTop: 8,
                overflow: 'hidden',
              }}
            >
              <div
                style={{
                  height: '100%',
                  width: `${barProgress * 100}%`,
                  borderRadius: 6,
                  backgroundImage: 'linear-gradient(90deg, #10b981, #34d399, #fcd34d)',
                }}
              />
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};
