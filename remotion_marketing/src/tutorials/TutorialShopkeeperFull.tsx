import React from 'react';
import {
  AbsoluteFill,
  Audio,
  interpolate,
  staticFile,
  useCurrentFrame,
  useVideoConfig,
} from 'remotion';
import { MockRegisterScreen } from '../components/ui_mockups/MockRegisterScreen';
import { MockRatesSetupScreen } from '../components/ui_mockups/MockRatesSetupScreen';
import { MockPrintersScreen } from '../components/ui_mockups/MockPrintersScreen';
import { MockAgentWindowModal } from '../components/ui_mockups/MockAgentWindowModal';
import { MockAndroidAppScreen } from '../components/ui_mockups/MockAndroidAppScreen';
import { MockQrPosterScreen } from '../components/ui_mockups/MockQrPosterScreen';
import { MockShopDashboardScreen } from '../components/ui_mockups/MockShopDashboardScreen';
import { MockCursor } from '../components/ui_mockups/MockCursor';

export const TutorialShopkeeperFull: React.FC = () => {
  const frame = useCurrentFrame();
  const { fps } = useVideoConfig();

  // Total 3900 frames (130 seconds = 2m 10s @ 30 FPS)
  // Scene 1: 0 - 600 (0s - 20s) -> Account Creation & Registration (MockRegisterScreen)
  // Scene 2: 600 - 1200 (20s - 40s) -> Rates & Payment Wallet Setup (MockRatesSetupScreen)
  // Scene 3: 1200 - 1740 (40s - 58s) -> Printers & Smart Routing (MockPrintersScreen)
  // Scene 4: 1740 - 2550 (58s - 85s) -> Windows Desktop Agent (.EXE) Setup (MockAgentWindowModal)
  // Scene 5: 2550 - 3300 (85s - 110s) -> Android Payment Listener APK (MockAndroidAppScreen)
  // Scene 6: 3300 - 3900 (110s - 130s) -> Counter QR Poster & Live Dashboard (MockQrPosterScreen & MockShopDashboardScreen)

  let activeStep = 1;
  let stepTitle = 'ধাপ ১: দোকান রেজিস্ট্রেশন ও অ্যাকাউন্ট তৈরি';
  let subtitle = 'printsheba.com-এ গিয়ে নাম, দোকানের নাম, ফোন ও পাসওয়ার্ড দিয়ে অ্যাকাউন্ট খুলুন।';

  if (frame >= 600 && frame < 1200) {
    activeStep = 2;
    stepTitle = 'ধাপ ২: প্রিন্ট রেট ও পেমেন্ট ওয়ালেট নির্ধারণ';
    subtitle = 'সাদাকালো ও কালার রেট দিন, বিকাশ ও নগদ নম্বর যুক্ত করুন— ১০০% টাকা আপনার ওয়ালেটে আসবে!';
  } else if (frame >= 1200 && frame < 1740) {
    activeStep = 3;
    stepTitle = 'ধাপ ৩: প্রিন্টার কনফিগারেশন ও অটো-রাউটিং';
    subtitle = 'সাদাকালো ও কালার প্রিন্টার নির্বাচন করুন— কাস্টমার কালার দিলে অটো কালার প্রিন্টারে যাবে!';
  } else if (frame >= 1740 && frame < 2550) {
    activeStep = 4;
    stepTitle = 'ধাপ ৪: উইন্ডোজ ডেস্কটপ এজেন্ট (.EXE) সেটআপ ও টেস্ট প্রিন্ট';
    subtitle = 'PrintShebaAgent.exe রান করুন, প্রিন্টার সিলেক্ট করে টেস্ট পেজ দিন ও সিস্টেম ট্রেতে মিনিমাইজ করুন।';
  } else if (frame >= 2550 && frame < 3300) {
    activeStep = 5;
    stepTitle = 'ধাপ ৫: অ্যান্ড্রয়েড পেমেন্ট লিসেনার অ্যাপ (APK) সেটআপ';
    subtitle = 'ফোনে APK ইনস্টল করে নোটিফিকেশন অ্যাক্সেস দিন— বিকাশ/নগদে টাকা আসলেই স্বয়ংক্রিয় প্রিন্ট হবে!';
  } else if (frame >= 3300) {
    activeStep = 6;
    stepTitle = 'ধাপ ৬: কাউন্টার QR পোস্টার ও লাইভ সেলস ড্যাশবোর্ড';
    subtitle = 'কাউন্টারে QR পোস্টার টানিয়ে দিন— ড্যাশবোর্ডে লাইভ সেলস ও ক্যাশ অনুমোদন পরিচালনা করুন!';
  }

  // Animated cursor for interactive clicks
  let cursorX = 960;
  let cursorY = 540;
  let isClicking = false;
  let cursorLabel = '';
  let showCursor = false;

  if (frame >= 400 && frame < 580) {
    // Scene 1: Click "বিনামূল্যে অ্যাকাউন্ট তৈরি করুন"
    showCursor = true;
    cursorLabel = '🚀 অ্যাকাউন্ট তৈরি করুন';
    cursorX = interpolate(frame, [400, 480], [900, 960], { extrapolateRight: 'clamp' });
    cursorY = interpolate(frame, [400, 480], [520, 610], { extrapolateRight: 'clamp' });
    if (frame >= 480 && frame < 520) isClicking = true;
  } else if (frame >= 980 && frame < 1180) {
    // Scene 2: Click "সেটিংস সেভ করুন"
    showCursor = true;
    cursorLabel = '💾 রেট সেভ করুন';
    cursorX = interpolate(frame, [980, 1050], [950, 1160], { extrapolateRight: 'clamp' });
    cursorY = interpolate(frame, [980, 1050], [550, 640], { extrapolateRight: 'clamp' });
    if (frame >= 1050 && frame < 1090) isClicking = true;
  } else if (frame >= 2100 && frame < 2280) {
    // Scene 4: Click "টেস্ট প্রিন্ট পাঠান" on Windows Agent
    showCursor = true;
    cursorLabel = '🖨️ টেস্ট প্রিন্ট';
    cursorX = interpolate(frame, [2100, 2170], [800, 940], { extrapolateRight: 'clamp' });
    cursorY = interpolate(frame, [2100, 2170], [450, 520], { extrapolateRight: 'clamp' });
    if (frame >= 2170 && frame < 2210) isClicking = true;
  } else if (frame >= 2780 && frame < 2960) {
    // Scene 5: Click Notification toggle on Android App
    showCursor = true;
    cursorLabel = '🔔 অনুমতি দিন';
    cursorX = interpolate(frame, [2780, 2840], [900, 1070], { extrapolateRight: 'clamp' });
    cursorY = interpolate(frame, [2780, 2840], [380, 395], { extrapolateRight: 'clamp' });
    if (frame >= 2840 && frame < 2880) isClicking = true;
  } else if (frame >= 3650 && frame < 3820) {
    // Scene 6: Click Cash Approve on Dashboard
    showCursor = true;
    cursorLabel = '✓ ক্যাশ অনুমোদন';
    cursorX = interpolate(frame, [3650, 3720], [1000, 1160], { extrapolateRight: 'clamp' });
    cursorY = interpolate(frame, [3650, 3720], [450, 520], { extrapolateRight: 'clamp' });
    if (frame >= 3720 && frame < 3760) isClicking = true;
  }

  return (
    <AbsoluteFill
      style={{
        backgroundColor: '#030712',
        color: '#ffffff',
        fontFamily: "'Hind Siliguri', sans-serif",
        overflow: 'hidden',
      }}
    >
      {/* Dynamic Ambient Background Glows */}
      <div
        style={{
          position: 'absolute',
          top: -200,
          right: '15%',
          width: 800,
          height: 600,
          borderRadius: '50%',
          backgroundImage: 'radial-gradient(circle, rgba(16,185,129,0.18) 0%, rgba(3,7,18,0) 70%)',
          pointerEvents: 'none',
        }}
      />
      <div
        style={{
          position: 'absolute',
          bottom: -150,
          left: '10%',
          width: 700,
          height: 500,
          borderRadius: '50%',
          backgroundImage: 'radial-gradient(circle, rgba(225,29,72,0.16) 0%, rgba(3,7,18,0) 70%)',
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
        {/* Brand & Badge */}
        <div style={{ display: 'flex', alignItems: 'center', gap: 16 }}>
          <div
            style={{
              width: 48,
              height: 48,
              borderRadius: 14,
              backgroundColor: '#10b981',
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
              color: '#000',
              fontSize: 24,
              fontWeight: 900,
              boxShadow: '0 8px 20px rgba(16,185,129,0.3)',
            }}
          >
            🏪
          </div>
          <div>
            <div style={{ display: 'flex', alignItems: 'center', gap: 10 }}>
              <span style={{ fontSize: 22, fontWeight: 900, letterSpacing: '0.02em' }}>
                PrintSheba <span style={{ color: '#34d399' }}>দোকানদার ফুল সেটআপ</span>
              </span>
              <span
                style={{
                  padding: '3px 10px',
                  borderRadius: 8,
                  backgroundColor: 'rgba(225,29,72,0.2)',
                  border: '1px solid #e11d48',
                  color: '#fb7185',
                  fontSize: 12,
                  fontWeight: 800,
                }}
              >
                ৫ মিনিটে পূর্ণাঙ্গ ডিজিটাল দোকান
              </span>
            </div>
            <span style={{ fontSize: 13, color: '#94a3b8' }}>
              রেজিস্ট্রেশন • রেট • প্রিন্টার • উইন্ডোজ এজেন্ট • অ্যান্ড্রয়েড অ্যাপ • কিউআর পোস্টার
            </span>
          </div>
        </div>

        {/* 6 Steps Progress Bar */}
        <div style={{ display: 'flex', alignItems: 'center', gap: 8 }}>
          {[
            { num: 1, label: 'রেজিস্ট্রেশন' },
            { num: 2, label: 'রেট সেটিংস' },
            { num: 3, label: 'প্রিন্টার' },
            { num: 4, label: 'উইন্ডোজ এজেন্ট' },
            { num: 5, label: 'অ্যান্ড্রয়েড অ্যাপ' },
            { num: 6, label: 'QR পোস্টার' },
          ].map((item) => {
            const isCurrent = activeStep === item.num;
            const isDone = activeStep > item.num;
            return (
              <div
                key={item.num}
                style={{
                  display: 'flex',
                  alignItems: 'center',
                  gap: 6,
                  padding: '5px 10px',
                  borderRadius: 10,
                  backgroundColor: isCurrent ? 'rgba(16,185,129,0.2)' : isDone ? 'rgba(56,189,248,0.15)' : '#0f172a',
                  border: isCurrent ? '1.5px solid #10b981' : isDone ? '1px solid #38bdf8' : '1px solid #1e293b',
                  color: isCurrent ? '#34d399' : isDone ? '#7dd3fc' : '#64748b',
                  fontSize: 11,
                  fontWeight: 800,
                }}
              >
                <span
                  style={{
                    width: 18,
                    height: 18,
                    borderRadius: '50%',
                    backgroundColor: isCurrent ? '#10b981' : isDone ? '#38bdf8' : '#1e293b',
                    color: isCurrent ? '#000000' : '#ffffff',
                    display: 'flex',
                    alignItems: 'center',
                    justifyContent: 'center',
                    fontSize: 10,
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
          bottom: 100,
          left: 0,
          right: 0,
          display: 'flex',
          alignItems: 'center',
          justifyContent: 'center',
          padding: '0 40px',
        }}
      >
        {/* SCENE 1: Registration Form (0 - 600 frames) */}
        {frame < 600 && (
          <div style={{ transform: 'scale(0.92)' }}>
            <MockRegisterScreen
              name="মো: রাসেল হোসেন"
              shopName="গ্রিন সাইবার ক্যাফে & ফটোকপি"
              phone="01712-345678"
              email="greencyber@gmail.com"
              isSubmitting={frame >= 490}
            />
          </div>
        )}

        {/* SCENE 2: Rates & Payment Wallet Numbers (600 - 1200 frames) */}
        {frame >= 600 && frame < 1200 && (
          <div style={{ transform: 'scale(0.92)' }}>
            <MockRatesSetupScreen
              bwRate={3.0}
              colorRate={10.0}
              duplexDiscount={1.0}
              bkashNumber="01712-345678"
              nagadNumber="01898-765432"
              hasColor={true}
              hasDuplex={true}
              isSaved={frame >= 1060}
            />
          </div>
        )}

        {/* SCENE 3: Printers & Smart Auto-Routing (1200 - 1740 frames) */}
        {frame >= 1200 && frame < 1740 && (
          <div style={{ transform: 'scale(0.92)' }}>
            <MockPrintersScreen
              hasAddedColor={true}
              highlightPrinter={frame < 1470 ? 'bw' : 'color'}
            />
          </div>
        )}

        {/* SCENE 4: Windows Desktop Agent (.EXE) C# Form (1740 - 2550 frames) */}
        {frame >= 1740 && frame < 2550 && (
          <div style={{ transform: 'scale(0.92)' }}>
            <MockAgentWindowModal
              status="● ক্লাউড সিঙ্ক সক্রিয় (Ready)"
              shopName="গ্রিন সাইবার ক্যাফে ও ফটোকপি"
              bwPrinter="HP LaserJet Pro MFP M428fdw"
              colorPrinter="Epson EcoTank L8050 Photo"
              isSaved={true}
              isTestPrinting={frame >= 2180 && frame < 2380}
              isMinimizedToTray={frame >= 2380}
            />
          </div>
        )}

        {/* SCENE 5: Android Payment Listener App (APK) (2550 - 3300 frames) */}
        {frame >= 2550 && frame < 3300 && (
          <div
            style={{
              display: 'flex',
              alignItems: 'center',
              gap: 60,
              maxWidth: 1100,
              width: '100%',
              justifyContent: 'center',
            }}
          >
            {/* Phone Mockup with Android App */}
            <MockAndroidAppScreen
              notificationAccessEnabled={frame >= 2850}
              serverUrl="https://printsheba.com"
              shopToken="ps_live_89f02c4819e4a3b70e"
              isConnected={true}
              simulatedNotification={frame >= 2950}
            />

            {/* Explanation card on right */}
            <div style={{ width: 480, display: 'flex', flexDirection: 'column', gap: 18 }}>
              <div
                style={{
                  padding: '22px 26px',
                  borderRadius: 20,
                  backgroundColor: '#0f172a',
                  border: '1.5px solid #10b981',
                  boxShadow: '0 10px 25px rgba(16,185,129,0.15)',
                  display: 'flex',
                  flexDirection: 'column',
                  gap: 12,
                }}
              >
                <div style={{ display: 'flex', alignItems: 'center', gap: 8 }}>
                  <span style={{ fontSize: 24 }}>📱</span>
                  <h3 style={{ fontSize: 18, fontWeight: 900, color: '#34d399', margin: 0 }}>
                    অ্যান্ড্রয়েড অটো-লিসেনার কীভাবে কাজ করে?
                  </h3>
                </div>
                <div style={{ display: 'flex', flexDirection: 'column', gap: 10, fontSize: 13, color: '#cbd5e1', lineHeight: 1.5 }}>
                  <div>
                    <strong>১. নোটিফিকেশন অ্যাক্সেস:</strong> কোনো ঝুঁকিপূর্ণ বা বিপজ্জনক এসএমএস পারমিশন লাগে না। ব্যাংক বা বিকাশের পুশ নোটিফিকেশন রিড করে কাজ করে।
                  </div>
                  <div>
                    <strong>২. ট্রানজেকশন অটো-ম্যাচিং:</strong> কাস্টমার যে পরিমাণ টাকা পাঠায়, তা পেন্ডিং অর্ডারের সাথে মিলিয়ের তাৎক্ষণিক পেইড হিসেবে মার্ক করে।
                  </div>
                  <div>
                    <strong>৩. অটো-প্রিন্ট ট্রিগার:</strong> পেমেন্ট নিশ্চিত হওয়ামাত্রই দোকানের কম্পিউটারের প্রিন্টার সাইলেন্টলি প্রিন্ট বের করে দেবে!
                  </div>
                </div>
              </div>

              <div style={{ padding: '12px 16px', borderRadius: 14, backgroundColor: 'rgba(236,72,153,0.15)', border: '1px solid #ec4899', color: '#f472b6', fontSize: 12, fontWeight: 700 }}>
                💡 দোকানদারকে আর মোবাইলে এসএমএস খুঁজে ট্রানজেকশন আইডি মেলানোর দরকার নেই!
              </div>
            </div>
          </div>
        )}

        {/* SCENE 6: Counter QR Poster & Live Shop Dashboard (3300 - 3900 frames) */}
        {frame >= 3300 && frame < 3600 && (
          <div style={{ transform: 'scale(0.92)' }}>
            <MockQrPosterScreen
              shopName="গ্রিন সাইবার ক্যাফে ও কম্পিউটার্স"
              isPrinting={frame >= 3450}
            />
          </div>
        )}

        {frame >= 3600 && (
          <div style={{ transform: 'scale(0.92)' }}>
            <MockShopDashboardScreen
              activeTab="dashboard"
              highlightAction="cash_approve"
              isApproving={frame >= 3730}
            />
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
              backgroundColor: '#10b981',
              color: '#000000',
              fontSize: 14,
              fontWeight: 900,
              boxShadow: '0 6px 16px rgba(16,185,129,0.3)',
            }}
          >
            {stepTitle}
          </div>
          <span style={{ fontSize: 16, color: '#e2e8f0', fontWeight: 600 }}>
            {subtitle}
          </span>
        </div>

        <div style={{ fontSize: 13, color: '#64748b', fontWeight: 700 }}>
          PrintSheba.com • দোকানদারদের সহজ ডিজিটাল পার্টনার
        </div>
      </div>

      {/* Synchronized Voiceover Audio from ElevenLabs */}
      <Audio src={staticFile('audio/Voiceover_Tutorial_Shopkeeper_Full.mp3')} />
    </AbsoluteFill>
  );
};
