import React from 'react';
import { interpolate, spring, useCurrentFrame, useVideoConfig } from 'remotion';
import { Background } from '../components/Background';
import { Badge, Title } from '../components/Typography';

export const Scene2Problems: React.FC = () => {
  const frame = useCurrentFrame();
  const { fps } = useVideoConfig();

  // Entrance springs matching speech timestamps:
  // 0s - 2.5s: "শুধু কি সময়ের অপচয়?"
  const titleAnim = spring({ frame: frame - 10, fps, config: { damping: 14 } });

  // 2.5s - 9.0s: "ভাবুন তো— কাস্টমারের ব্যক্তিগত ছবি, এনআইডি কিংবা জরুরি সার্টিফিকেট পিসিতে ডাউনলোড হয়ে থাকছে"
  const card1 = spring({ frame: frame - 75, fps, config: { damping: 14 } });

  // 9.0s - 14.5s: "এতে যেকোনো মুহূর্তে ঘটতে পারে মারাত্মক প্রাইভেসি ব্রেক ও বিব্রতকর পরিস্থিতি!"
  const card2 = spring({ frame: frame - 270, fps, config: { damping: 14 } });

  // 14.5s - 22.2s: "তার ওপর ভিড়ের মধ্যে কে কয় পেজ প্রিন্ট করল, কালার নাকি সাদাকালো— সেই হিসাব মেলাতেও তৈরি হয় চরম বিশৃঙ্খলা"
  const card3 = spring({ frame: frame - 430, fps, config: { damping: 14 } });

  // Blinking privacy alert
  const alertPulse = Math.sin(frame * 0.15) > 0;

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

      {/* Top Badge */}
      <div style={{ marginBottom: 20 }}>
        <Badge text="৩টি লুকায়িত কিন্তু মারাত্মক ঝুঁকি" icon="🚨" color="amber" delay={5} />
      </div>

      {/* Section Title */}
      <Title size={48} delay={10}>
        শুধু কি সময়ের অপচয়?
      </Title>
      <div style={{ marginTop: 10 }}>
        <Title size={64} gradient="rose" delay={25}>
          ভাবুন তো— আপনার দোকানে কী ঘটছে?
        </Title>
      </div>

      {/* 3 Main Problem Columns */}
      <div
        style={{
          display: 'grid',
          gridTemplateColumns: 'repeat(3, 1fr)',
          gap: 30,
          marginTop: 60,
          width: '100%',
          maxWidth: 1440,
        }}
      >
        {/* Card 1: Privacy Leak Threat (Enters at 2.5s) */}
        <div
          style={{
            transform: `translateY(${interpolate(card1, [0, 1], [60, 0])}px) scale(${card1})`,
            opacity: card1,
            backgroundColor: 'rgba(20, 20, 35, 0.85)',
            border: alertPulse ? '2px solid #ef4444' : '2px solid rgba(239, 68, 68, 0.3)',
            borderRadius: 28,
            padding: 35,
            display: 'flex',
            flexDirection: 'column',
            justifyContent: 'space-between',
            backdropFilter: 'blur(20px)',
            boxShadow: alertPulse ? '0 0 35px rgba(239, 68, 68, 0.3)' : '0 15px 30px rgba(0,0,0,0.5)',
            transition: 'border 0.2s, box-shadow 0.2s',
          }}
        >
          <div>
            <div
              style={{
                width: 70,
                height: 70,
                borderRadius: 20,
                backgroundColor: 'rgba(239, 68, 68, 0.15)',
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'center',
                fontSize: 36,
                border: '1.5px solid rgba(239, 68, 68, 0.3)',
              }}
            >
              🔓
            </div>
            <h3
              style={{
                fontSize: 26,
                fontWeight: 900,
                color: '#f87171',
                marginTop: 25,
                fontFamily: "'Hind Siliguri', sans-serif",
              }}
            >
              মারাত্মক প্রাইভেসি ঝুঁকি!
            </h3>
            <p
              style={{
                fontSize: 18,
                color: '#cbd5e1',
                lineHeight: 1.6,
                marginTop: 15,
                fontFamily: "'Hind Siliguri', sans-serif",
              }}
            >
              কাস্টমারের পার্সোনাল ছবি, এনআইডি কিংবা সার্টিফিকেট আপনার পিসিতে ডাউনলোড হয়ে জমা থাকছে। যেকোনো মুহূর্তে ঘটতে
              পারে কাস্টমারের গোপন তথ্যের অপব্যবহার ও চরম বিব্রতকর পরিস্থিতি!
            </p>
          </div>
          <div
            style={{
              marginTop: 25,
              padding: '10px 16px',
              borderRadius: 12,
              backgroundColor: 'rgba(239, 68, 68, 0.1)',
              color: '#fca5a5',
              fontSize: 14,
              fontWeight: 800,
              fontFamily: "'Hind Siliguri', sans-serif",
            }}
          >
            ⚠️ Downloads ফোল্ডারে ব্যক্তিগত ফাইলের স্তূপ
          </div>
        </div>

        {/* Card 2: Time and Efficiency Loss (Enters at 9s) */}
        <div
          style={{
            transform: `translateY(${interpolate(card2, [0, 1], [60, 0])}px) scale(${card2})`,
            opacity: card2,
            backgroundColor: 'rgba(20, 20, 35, 0.85)',
            border: '2px solid rgba(245, 158, 11, 0.3)',
            borderRadius: 28,
            padding: 35,
            display: 'flex',
            flexDirection: 'column',
            justifyContent: 'space-between',
            backdropFilter: 'blur(20px)',
            boxShadow: '0 15px 30px rgba(0,0,0,0.5)',
          }}
        >
          <div>
            <div
              style={{
                width: 70,
                height: 70,
                borderRadius: 20,
                backgroundColor: 'rgba(245, 158, 11, 0.15)',
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'center',
                fontSize: 36,
                border: '1.5px solid rgba(245, 158, 11, 0.3)',
              }}
            >
              ⏳
            </div>
            <h3
              style={{
                fontSize: 26,
                fontWeight: 900,
                color: '#fcd34d',
                marginTop: 25,
                fontFamily: "'Hind Siliguri', sans-serif",
              }}
            >
              প্রতিটি কাস্টমারে ১০ মিনিট অপচয়
            </h3>
            <p
              style={{
                fontSize: 18,
                color: '#cbd5e1',
                lineHeight: 1.6,
                marginTop: 15,
                fontFamily: "'Hind Siliguri', sans-serif",
              }}
            >
              কাস্টমার দাঁড়িয়ে থাকে, কেবল লাগায়, ফাইল খুঁজে বের করে, হোয়াটসঅ্যাপ খোলে... সারাদিনের মূল্যবান কর্মঘণ্টা
              নষ্ট হয় শুধু ফাইল ট্রান্সফার করতেই!
            </p>
          </div>
          <div
            style={{
              marginTop: 25,
              padding: '10px 16px',
              borderRadius: 12,
              backgroundColor: 'rgba(245, 158, 11, 0.1)',
              color: '#fde68a',
              fontSize: 14,
              fontWeight: 800,
              fontFamily: "'Hind Siliguri', sans-serif",
            }}
          >
            📉 কাজের গতি ৮০% পর্যন্ত কমে যায়
          </div>
        </div>

        {/* Card 3: Billing & Financial Mess (Enters at 14.5s) */}
        <div
          style={{
            transform: `translateY(${interpolate(card3, [0, 1], [60, 0])}px) scale(${card3})`,
            opacity: card3,
            backgroundColor: 'rgba(20, 20, 35, 0.85)',
            border: '2px solid rgba(168, 85, 247, 0.3)',
            borderRadius: 28,
            padding: 35,
            display: 'flex',
            flexDirection: 'column',
            justifyContent: 'space-between',
            backdropFilter: 'blur(20px)',
            boxShadow: '0 15px 30px rgba(0,0,0,0.5)',
          }}
        >
          <div>
            <div
              style={{
                width: 70,
                height: 70,
                borderRadius: 20,
                backgroundColor: 'rgba(168, 85, 247, 0.15)',
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'center',
                fontSize: 36,
                border: '1.5px solid rgba(168, 85, 247, 0.3)',
              }}
            >
              💸
            </div>
            <h3
              style={{
                fontSize: 26,
                fontWeight: 900,
                color: '#d8b4fe',
                marginTop: 25,
                fontFamily: "'Hind Siliguri', sans-serif",
              }}
            >
              টাকা ও হিসাবের গোলমাল
            </h3>
            <p
              style={{
                fontSize: 18,
                color: '#cbd5e1',
                lineHeight: 1.6,
                marginTop: 15,
                fontFamily: "'Hind Siliguri', sans-serif",
              }}
            >
              ভিড়ের মাঝে কে কয় পৃষ্ঠা প্রিন্ট করল, কালার নাকি ব্ল্যাক অ্যান্ড হোয়াইট— মনে রাখা কঠিন। দিনশেষে হিসেবে গরমিল ও
              টাকা বাকি পড়ার ঝামেলা নিত্যদিনের সঙ্গী।
            </p>
          </div>
          <div
            style={{
              marginTop: 25,
              padding: '10px 16px',
              borderRadius: 12,
              backgroundColor: 'rgba(168, 85, 247, 0.1)',
              color: '#e9d5ff',
              fontSize: 14,
              fontWeight: 800,
              fontFamily: "'Hind Siliguri', sans-serif",
            }}
          >
            📊 ম্যানুয়াল হিসাবের চরম বিশৃঙ্খলা
          </div>
        </div>
      </div>
    </div>
  );
};
