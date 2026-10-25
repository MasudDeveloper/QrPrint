import React from 'react';
import {
  AbsoluteFill,
  interpolate,
  useCurrentFrame,
  useVideoConfig,
} from 'remotion';
import { MockShopDashboardScreen } from '../components/ui_mockups/MockShopDashboardScreen';
import { MockRatesSetupScreen } from '../components/ui_mockups/MockRatesSetupScreen';
import { MockPrintersScreen } from '../components/ui_mockups/MockPrintersScreen';
import { MockAgentDownloadScreen } from '../components/ui_mockups/MockAgentDownloadScreen';
import { MockQrPosterScreen } from '../components/ui_mockups/MockQrPosterScreen';
import { MockCursor } from '../components/ui_mockups/MockCursor';

export const TutorialShopkeeper: React.FC = () => {
  const frame = useCurrentFrame();
  const { fps } = useVideoConfig();

  // Total 2700 frames (90 seconds @ 30 FPS)
  // Scene 1: 0 - 450 (0 - 15s) -> Welcome & Shop Dashboard
  // Scene 2: 450 - 870 (15 - 29s) -> Print Rates & Wallet Setup
  // Scene 3: 870 - 1290 (29 - 43s) -> Printer Setup & Auto-Routing
  // Scene 4: 1290 - 1770 (43 - 59s) -> Windows Desktop Agent (.EXE)
  // Scene 5: 1770 - 2220 (59 - 74s) -> Counter QR Poster Print & Display
  // Scene 6: 2220 - 2700 (74 - 90s) -> Live Queue, Cash Approve & Daily Sales

  let activeStep = 1;
  let stepTitle = 'স্বাগতম: মাত্র ৫ মিনিটে সম্পূর্ণ দোকান অটোমেশন';
  let subtitle = 'printsheba.com-এ রেজিস্ট্রেশন করে আপনার শপ ড্যাশবোর্ডে লগইন করুন।';

  if (frame >= 450 && frame < 870) {
    activeStep = 1;
    stepTitle = 'ধাপ ১: প্রিন্ট রেট ও পেমেন্ট ওয়ালেট কনফিগারেশন';
    subtitle = 'সাদাকালো ও কালার রেট দিন, বিকাশ ও নগদ নম্বর যুক্ত করুন— টাকা সরাসরি আপনার নম্বরে যাবে!';
  } else if (frame >= 870 && frame < 1290) {
    activeStep = 2;
    stepTitle = 'ধাপ ২: প্রিন্টার কনফিগারেশন ও অটো-রাউটিং';
    subtitle = 'দোকানের প্রিন্টারগুলো যুক্ত করুন। কালার ফাইল এলে স্বয়ংক্রিয়ভাবে কালার প্রিন্টারে চলে যাবে!';
  } else if (frame >= 1290 && frame < 1770) {
    activeStep = 3;
    stepTitle = 'ধাপ ৩: উইন্ডোজ অটো-প্রিন্ট এজেন্ট সফটওয়্যার চালু';
    subtitle = 'PrintShebaAgent.exe ডাউনলোড করে রান করুন— এটি ব্যাকগ্রাউন্ডে স্বয়ংক্রিয়ভাবে প্রিন্ট পরিচালনা করবে।';
  } else if (frame >= 1770 && frame < 2220) {
    activeStep = 4;
    stepTitle = 'ধাপ ৪: কাউন্টার QR পোস্টার প্রিন্ট ও ডিসপ্লে';
    subtitle = 'আকর্ষণীয় রেডিমেড কিউআর পোস্টার প্রিন্ট করে দোকানের কাউন্টারে টানিয়ে দিন।';
  } else if (frame >= 2220) {
    activeStep = 5;
    stepTitle = 'ধাপ ৫: লাইভ ড্যাশবোর্ড ও ক্যাশ অনুমোদন';
    subtitle = 'কাস্টমারের প্রতিটি প্রিন্ট ও সেলস লাইভ মনিটর করুন, ক্যাশ পেমেন্ট এক ক্লিকে অনুমোদন দিন!';
  }

  // Cursor animation triggers
  let cursorX = 960;
  let cursorY = 540;
  let isClicking = false;
  let cursorLabel = '';
  let showCursor = false;

  if (frame >= 680 && frame < 850) {
    // Save rates button click
    showCursor = true;
    cursorLabel = '💾 সেভ করুন';
    cursorX = interpolate(frame, [680, 760], [1050, 1150], { extrapolateRight: 'clamp' });
    cursorY = interpolate(frame, [680, 760], [580, 640], { extrapolateRight: 'clamp' });
    if (frame >= 760 && frame < 800) isClicking = true;
  } else if (frame >= 1480 && frame < 1650) {
    // Copy token click
    showCursor = true;
    cursorLabel = '📋 কপি টোকেন';
    cursorX = interpolate(frame, [1480, 1540], [600, 750], { extrapolateRight: 'clamp' });
    cursorY = interpolate(frame, [1480, 1540], [450, 580], { extrapolateRight: 'clamp' });
    if (frame >= 1540 && frame < 1580) isClicking = true;
  } else if (frame >= 2400 && frame < 2550) {
    // Cash approve click
    showCursor = true;
    cursorLabel = '✓ ক্যাশ অনুমোদন';
    cursorX = interpolate(frame, [2400, 2460], [1050, 1160], { extrapolateRight: 'clamp' });
    cursorY = interpolate(frame, [2400, 2460], [450, 520], { extrapolateRight: 'clamp' });
    if (frame >= 2460 && frame < 2500) isClicking = true;
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
      {/* Background Gradients */}
      <div
        style={{
          position: 'absolute',
          top: -200,
          right: '15%',
          width: 800,
          height: 600,
          borderRadius: '50%',
          backgroundImage: 'radial-gradient(circle, rgba(16,185,129,0.15) 0%, rgba(3,7,18,0) 70%)',
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
          backgroundImage: 'radial-gradient(circle, rgba(225,29,72,0.15) 0%, rgba(3,7,18,0) 70%)',
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
                PrintSheba <span style={{ color: '#34d399' }}>দোকানদার গাইড</span>
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
                ৫ মিনিটে পূর্ণাঙ্গ সেটআপ ও পরিচালনা
              </span>
            </div>
            <span style={{ fontSize: 13, color: '#94a3b8' }}>
              ফটোকপি ও প্রিন্টিং শপের ১০০% ডিজিটাল সমাধান
            </span>
          </div>
        </div>

        {/* 5 Steps Pipeline Indicator */}
        <div style={{ display: 'flex', alignItems: 'center', gap: 10 }}>
          {[
            { num: 1, label: 'রেট সেটিংস' },
            { num: 2, label: 'প্রিন্টার' },
            { num: 3, label: 'উইন্ডোজ এজেন্ট' },
            { num: 4, label: 'QR পোস্টার' },
            { num: 5, label: 'লাইভ সেলস' },
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
                  padding: '6px 12px',
                  borderRadius: 10,
                  backgroundColor: isCurrent ? 'rgba(16,185,129,0.2)' : isDone ? 'rgba(56,189,248,0.15)' : '#0f172a',
                  border: isCurrent ? '1.5px solid #10b981' : isDone ? '1px solid #38bdf8' : '1px solid #1e293b',
                  color: isCurrent ? '#34d399' : isDone ? '#7dd3fc' : '#64748b',
                  fontSize: 12,
                  fontWeight: 800,
                }}
              >
                <span
                  style={{
                    width: 20,
                    height: 20,
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

      {/* Main Content Area / Interactive UI Presentation */}
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
        {/* SCENE 1: Welcome & Dashboard Overview (0 - 450) */}
        {frame < 450 && (
          <div style={{ transform: 'scale(0.92)' }}>
            <MockShopDashboardScreen activeTab="dashboard" />
          </div>
        )}

        {/* SCENE 2: Rates & Payment Numbers (450 - 870) */}
        {frame >= 450 && frame < 870 && (
          <div style={{ transform: 'scale(0.92)' }}>
            <MockRatesSetupScreen
              bwRate={3.0}
              colorRate={10.0}
              duplexDiscount={1.0}
              bkashNumber="01712-345678"
              nagadNumber="01898-765432"
              hasColor={true}
              hasDuplex={true}
              isSaved={frame >= 770}
            />
          </div>
        )}

        {/* SCENE 3: Printers & Smart Auto-Routing (870 - 1290) */}
        {frame >= 870 && frame < 1290 && (
          <div style={{ transform: 'scale(0.92)' }}>
            <MockPrintersScreen
              hasAddedColor={true}
              highlightPrinter={frame < 1080 ? 'bw' : 'color'}
            />
          </div>
        )}

        {/* SCENE 4: Windows Desktop Agent (.EXE) Setup (1290 - 1770) */}
        {frame >= 1290 && frame < 1770 && (
          <div style={{ transform: 'scale(0.92)' }}>
            <MockAgentDownloadScreen
              apiToken="ps_live_89f02c4819e4a3b70e"
              isTokenCopied={frame >= 1550}
              showDesktopPreview={true}
              isConnected={true}
            />
          </div>
        )}

        {/* SCENE 5: Counter QR Poster Generator & Print (1770 - 2220) */}
        {frame >= 1770 && frame < 2220 && (
          <div style={{ transform: 'scale(0.92)' }}>
            <MockQrPosterScreen
              shopName="গ্রিন সাইবার ক্যাফে ও কম্পিউটার্স"
              isPrinting={frame >= 2000}
            />
          </div>
        )}

        {/* SCENE 6: Live Orders, Cash Approval & Daily Sales (2220 - 2700) */}
        {frame >= 2220 && (
          <div style={{ transform: 'scale(0.92)' }}>
            <MockShopDashboardScreen
              activeTab="dashboard"
              highlightAction="cash_approve"
              isApproving={frame >= 2470}
            />
          </div>
        )}

        {/* Animated Mouse Cursor */}
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
    </AbsoluteFill>
  );
};
