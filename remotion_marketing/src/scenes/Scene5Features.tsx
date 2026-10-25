import React from 'react';
import { interpolate, spring, useCurrentFrame, useVideoConfig } from 'remotion';
import { Background } from '../components/Background';
import { Badge, Title } from '../components/Typography';

interface FeatureCardProps {
  icon: string;
  tag: string;
  title: string;
  description: string;
  color: 'emerald' | 'blue' | 'rose' | 'amber' | 'purple';
  delay: number;
}

const FeatureCard: React.FC<FeatureCardProps> = ({
  icon,
  tag,
  title,
  description,
  color,
  delay,
}) => {
  const frame = useCurrentFrame();
  const { fps } = useVideoConfig();

  const anim = spring({ frame: frame - delay, fps, config: { damping: 14 } });

  const colorConfig = {
    emerald: { border: 'rgba(16, 185, 129, 0.4)', bg: 'rgba(16, 185, 129, 0.1)', text: '#6ee7b7' },
    blue: { border: 'rgba(59, 130, 246, 0.4)', bg: 'rgba(59, 130, 246, 0.1)', text: '#93c5fd' },
    rose: { border: 'rgba(244, 63, 94, 0.4)', bg: 'rgba(244, 63, 94, 0.1)', text: '#fda4af' },
    amber: { border: 'rgba(245, 158, 11, 0.4)', bg: 'rgba(245, 158, 11, 0.1)', text: '#fcd34d' },
    purple: { border: 'rgba(168, 85, 247, 0.4)', bg: 'rgba(168, 85, 247, 0.1)', text: '#d8b4fe' },
  }[color];

  return (
    <div
      style={{
        transform: `translateY(${interpolate(anim, [0, 1], [40, 0])}px) scale(${anim})`,
        opacity: anim,
        backgroundColor: 'rgba(15, 23, 42, 0.85)',
        border: `1.5px solid ${colorConfig.border}`,
        borderRadius: 24,
        padding: '24px 28px',
        display: 'flex',
        alignItems: 'flex-start',
        gap: 20,
        backdropFilter: 'blur(16px)',
        boxShadow: '0 12px 28px rgba(0,0,0,0.4)',
      }}
    >
      <div
        style={{
          width: 56,
          height: 56,
          borderRadius: 16,
          backgroundColor: colorConfig.bg,
          border: `1.5px solid ${colorConfig.border}`,
          display: 'flex',
          alignItems: 'center',
          justifyContent: 'center',
          fontSize: 28,
          flexShrink: 0,
        }}
      >
        {icon}
      </div>
      <div>
        <span
          style={{
            fontSize: 12,
            fontWeight: 800,
            textTransform: 'uppercase',
            letterSpacing: '0.05em',
            color: colorConfig.text,
            fontFamily: "'Plus Jakarta Sans', sans-serif",
          }}
        >
          {tag}
        </span>
        <h4
          style={{
            fontSize: 22,
            fontWeight: 900,
            color: '#ffffff',
            margin: '4px 0 8px 0',
            fontFamily: "'Hind Siliguri', sans-serif",
          }}
        >
          {title}
        </h4>
        <p
          style={{
            fontSize: 15,
            color: '#cbd5e1',
            margin: 0,
            lineHeight: 1.5,
            fontFamily: "'Hind Siliguri', sans-serif",
          }}
        >
          {description}
        </p>
      </div>
    </div>
  );
};

export const Scene5Features: React.FC = () => {
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
      <Background variant="purple" />

      {/* Top Badge */}
      <div style={{ marginBottom: 15 }}>
        <Badge text="অত্যাধুনিক প্রযুক্তি ও পাওয়ারফুল ফিচার" icon="⚡" color="purple" delay={5} />
      </div>

      <Title size={44} delay={10}>
        প্রিন্টসেবা কেন সবার চেয়ে এগিয়ে?
      </Title>
      <div style={{ marginTop: 8 }}>
        <Title size={56} gradient="gold" delay={25}>
          ৫টি গেম-চেঞ্জিং ফিচার যা আপনার ব্যবসাকে বদলে দেবে
        </Title>
      </div>

      {/* Features Grid Synchronized to Voice Cues */}
      <div
        style={{
          display: 'grid',
          gridTemplateColumns: 'repeat(2, 1fr)',
          gap: 22,
          marginTop: 45,
          width: '100%',
          maxWidth: 1400,
        }}
      >
        {/* Feature 1 (Voice at 3s / 90f: "আমাদের স্মার্ট ডেস্কটপ প্রিন্ট এজেন্ট") */}
        <FeatureCard
          icon="🖥️"
          tag="Automated Spooler"
          title="উইন্ডোজ ডেস্কটপ প্রিন্ট এজেন্ট"
          description="দোকানের কম্পিউটারে ব্যাকগ্রাউন্ডে চলে। নতুন প্রিন্ট আসলে কোনো মাউস ক্লিক ছাড়াই সাথে সাথে অটো প্রিন্ট করে দেয়!"
          color="emerald"
          delay={90}
        />

        {/* Feature 2 (Voice at 10s / 300f: "কালার এবং সাদাকালো পেজ আলাদা প্রিন্টারে অটোমেটিক চলে যায়") */}
        <FeatureCard
          icon="🖨️"
          tag="Smart Printer Routing"
          title="কালার ও সাদাকালো অটো-রাউটিং"
          description="কাস্টমার কালার পেজ সিলেক্ট করলে কালার প্রিন্টারে যাবে, আর সাদাকালো পেজ স্বয়ংক্রিয়ভাবে সাদাকালো প্রিন্টারে চলে যাবে।"
          color="blue"
          delay={300}
        />

        {/* Feature 3 (Voice at 16s / 480f: "আমাদের জিরো-রিটেনশন পলিসি— প্রিন্ট শেষ হওয়া মাত্র ফাইল ডিলিট") */}
        <FeatureCard
          icon="🛡️"
          tag="100% Privacy"
          title="জিরো-রিটেনশন পলিসি (Zero Retention)"
          description="প্রিন্ট সম্পন্ন হওয়ামাত্রই সার্ভার ও মেমোরি থেকে কাস্টমারের ফাইল চিরতরে মুছে যায়। কাস্টমারের গোপনীয়তা থাকে সম্পূর্ণ সুরক্ষিত!"
          color="rose"
          delay={480}
        />

        {/* Feature 4 (Voice at 23s / 690f: "সাথে রয়েছে স্মার্ট এনআইডি প্রিন্ট") */}
        <FeatureCard
          icon="🆔"
          tag="Smart Document"
          title="স্মার্ট এনআইডি ও এপিঠ-ওপিঠ প্রিন্ট"
          description="এনআইডি কার্ডের এপিঠ-ওপিঠ স্বয়ংক্রিয়ভাবে পারফেক্ট স্কেলে একই পৃষ্ঠায় সাজিয়ে নির্ভুল সাইজে প্রিন্ট করে।"
          color="purple"
          delay={690}
        />

        {/* Feature 5 (Voice at 26s / 780f: "ও মোবাইল থেকেই দোকানের প্রতিদিনের মোট আয় ও হিসাবের লাইভ ড্যাশবোর্ড") */}
        <div style={{ gridColumn: 'span 2' }}>
          <FeatureCard
            icon="📱"
            tag="Real-Time Analytics"
            title="মোবাইল লাইভ ড্যাশবোর্ড ও ডিজিটাল পেমেন্ট ট্র্যাকিং"
            description="দোকানে না থেকেও মোবাইল থেকেই দেখুন আজকের মোট আয়, মোট কত পাতা প্রিন্ট হলো এবং বিকাশ/নগদ/ক্যাশ লেনদেনের নিখুঁত হিসাব!"
            color="amber"
            delay={780}
          />
        </div>
      </div>
    </div>
  );
};
