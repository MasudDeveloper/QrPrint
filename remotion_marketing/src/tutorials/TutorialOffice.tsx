import React from 'react';
import {
  AbsoluteFill,
  interpolate,
  useCurrentFrame,
  useVideoConfig,
} from 'remotion';
import { MockOfficeDirectPrintScreen } from '../components/ui_mockups/MockOfficeDirectPrintScreen';
import { MockNidPortalScreen } from '../components/ui_mockups/MockNidPortalScreen';
import { MockCursor } from '../components/ui_mockups/MockCursor';

export const TutorialOffice: React.FC = () => {
  const frame = useCurrentFrame();
  const { fps } = useVideoConfig();

  // Total 1800 frames (60 seconds @ 30 FPS)
  // Scene 1: 0 - 600 (0 - 20s) -> Office Free Direct Print (No billing, instant 1-click spool)
  // Scene 2: 600 - 1320 (20 - 44s) -> Smart AI NID Card Tool (Front/Back photo, auto crop & A4 align)
  // Scene 3: 1320 - 1800 (44 - 60s) -> 30-Second No Photoshop Advantage & Summary

  let activeTab = 1;
  let stepTitle = 'অফিস ফ্রি প্রিন্ট: কোনো বিল বা পেমেন্ট ছাড়াই সরাসরি প্রিন্ট';
  let subtitle = 'দোকানের নিজস্ব হিসাব বা জরুরি ফাইল এক ক্লিকে সরাসরি প্রিন্টারে পাঠান।';

  if (frame >= 600 && frame < 1320) {
    activeTab = 2;
    stepTitle = 'স্মার্ট এনআইডি টুল: পারফেক্ট এপিঠ-ওপিঠ A4 প্রিন্ট';
    subtitle = 'ক্যামেরা দিয়ে ছবি তুলুন— আমাদের এআই স্বয়ংক্রিয়ভাবে সোজা ও ক্রপ করে নিখুঁত মাপে বসিয়ে দেবে!';
  } else if (frame >= 1320) {
    activeTab = 3;
    stepTitle = 'কোনো ফটোশপ লাগবে না: মাত্র ৩০ সেকেন্ডে রেডি!';
    subtitle = 'গ্রাহক সেবা হোক কিংবা নিজের কাজ— নির্ভুল ও দ্রুততম প্রিন্টিং অভিজ্ঞতা।';
  }

  // Cursor interactions
  let cursorX = 960;
  let cursorY = 540;
  let isClicking = false;
  let cursorLabel = '';
  let showCursor = false;

  if (frame >= 350 && frame < 520) {
    // Click Direct Print Button
    showCursor = true;
    cursorLabel = '⚡ সরাসরি প্রিন্ট করুন';
    cursorX = interpolate(frame, [350, 420], [800, 1020], { extrapolateRight: 'clamp' });
    cursorY = interpolate(frame, [350, 420], [450, 620], { extrapolateRight: 'clamp' });
    if (frame >= 420 && frame < 460) isClicking = true;
  } else if (frame >= 1050 && frame < 1220) {
    // Click NID Print Button
    showCursor = true;
    cursorLabel = '🖨️ অফিস প্রিন্ট (ফ্রি)';
    cursorX = interpolate(frame, [1050, 1120], [500, 560], { extrapolateRight: 'clamp' });
    cursorY = interpolate(frame, [1050, 1120], [400, 630], { extrapolateRight: 'clamp' });
    if (frame >= 1120 && frame < 1160) isClicking = true;
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
          left: '25%',
          width: 800,
          height: 600,
          borderRadius: '50%',
          backgroundImage: 'radial-gradient(circle, rgba(99,102,241,0.2) 0%, rgba(3,7,18,0) 70%)',
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
        {/* Brand & Badge */}
        <div style={{ display: 'flex', alignItems: 'center', gap: 16 }}>
          <div
            style={{
              width: 48,
              height: 48,
              borderRadius: 14,
              backgroundColor: '#6366f1',
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
              color: '#fff',
              fontSize: 24,
              fontWeight: 900,
              boxShadow: '0 8px 20px rgba(99,102,241,0.3)',
            }}
          >
            🏢
          </div>
          <div>
            <div style={{ display: 'flex', alignItems: 'center', gap: 10 }}>
              <span style={{ fontSize: 22, fontWeight: 900, letterSpacing: '0.02em' }}>
                PrintSheba <span style={{ color: '#818cf8' }}>অফিস ও স্মার্ট NID গাইড</span>
              </span>
              <span
                style={{
                  padding: '3px 10px',
                  borderRadius: 8,
                  backgroundColor: 'rgba(99,102,241,0.2)',
                  border: '1px solid #6366f1',
                  color: '#a5b4fc',
                  fontSize: 12,
                  fontWeight: 800,
                }}
              >
                Office Mode &amp; Smart Tools
              </span>
            </div>
            <span style={{ fontSize: 13, color: '#94a3b8' }}>
              দোকানের নিজস্ব ফ্রি প্রিন্ট ও এআই-চালিত এনআইডি প্রসেসিং
            </span>
          </div>
        </div>

        {/* 2 Main Features Switcher */}
        <div style={{ display: 'flex', alignItems: 'center', gap: 12 }}>
          <div
            style={{
              padding: '8px 16px',
              borderRadius: 12,
              backgroundColor: activeTab === 1 ? 'rgba(16,185,129,0.2)' : '#0f172a',
              border: activeTab === 1 ? '1.5px solid #10b981' : '1px solid #1e293b',
              color: activeTab === 1 ? '#34d399' : '#64748b',
              fontSize: 13,
              fontWeight: 800,
              display: 'flex',
              alignItems: 'center',
              gap: 8,
            }}
          >
            <span>⚡</span>
            <span>১. অফিস ফ্রি প্রিন্ট</span>
          </div>

          <div
            style={{
              padding: '8px 16px',
              borderRadius: 12,
              backgroundColor: activeTab >= 2 ? 'rgba(99,102,241,0.2)' : '#0f172a',
              border: activeTab >= 2 ? '1.5px solid #6366f1' : '1px solid #1e293b',
              color: activeTab >= 2 ? '#a5b4fc' : '#64748b',
              fontSize: 13,
              fontWeight: 800,
              display: 'flex',
              alignItems: 'center',
              gap: 8,
            }}
          >
            <span>🪪</span>
            <span>২. স্মার্ট এনআইডি টুল</span>
          </div>
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
        {/* SCENE 1: Office Free Direct Print (0 - 600) */}
        {frame < 600 && (
          <div style={{ transform: 'scale(0.92)' }}>
            <MockOfficeDirectPrintScreen
              fileName="Monthly_Accounts_Oct2026.xlsx"
              fileSize="420 KB (৩টি পেজ)"
              printerName="HP LaserJet Pro MFP M428fdw"
              copies={3}
              isPrinting={frame >= 430}
              statusText="সরাসরি প্রিন্টারে পাঠানো হয়েছে"
            />
          </div>
        )}

        {/* SCENE 2: Smart AI NID Card Tool (600 - 1320) */}
        {frame >= 600 && frame < 1320 && (
          <div style={{ transform: 'scale(0.92)' }}>
            <MockNidPortalScreen
              step={frame >= 1130 ? 'printing' : 'preview_a4'}
              frontCaptured={true}
              backCaptured={true}
              copies={1}
            />
          </div>
        )}

        {/* SCENE 3: Feature Comparison & Speed Advantage (1320 - 1800) */}
        {frame >= 1320 && (
          <div
            style={{
              display: 'flex',
              gap: 40,
              alignItems: 'center',
              maxWidth: 1100,
              width: '100%',
              justifyContent: 'center',
            }}
          >
            {/* Left Card: Traditional Slow Way */}
            <div
              style={{
                flex: 1,
                padding: '36px',
                borderRadius: 24,
                backgroundColor: '#0f172a',
                border: '1.5px solid #334155',
                display: 'flex',
                flexDirection: 'column',
                gap: 18,
                opacity: 0.8,
              }}
            >
              <div style={{ display: 'inline-flex', padding: '4px 12px', borderRadius: 8, backgroundColor: 'rgba(239,68,68,0.15)', color: '#f87171', fontSize: 12, fontWeight: 800, width: 'fit-content' }}>
                ❌ সনাতন পদ্ধতি (Photoshop / ম্যানুয়াল)
              </div>
              <h3 style={{ fontSize: 22, fontWeight: 900, margin: 0, color: '#cbd5e1' }}>
                ১০ থেকে ১৫ মিনিট অপচয়
              </h3>
              <div style={{ display: 'flex', flexDirection: 'column', gap: 12, fontSize: 13, color: '#94a3b8' }}>
                <div>• ছবি তুলে কম্পিউটারে পাঠানো</div>
                <div>• ফটোশপে ওপেন করে ম্যানুয়ালি রোটেট ও ক্রপ করা</div>
                <div>• A4 সাইজে কার্ডের সঠিক মাপ মেপে বসানো</div>
                <div>• দোকানে কাস্টমারের দীর্ঘ ভিড় ও অসন্তুষ্টি</div>
              </div>
            </div>

            {/* VS Divider */}
            <div style={{ fontSize: 24, fontWeight: 900, color: '#64748b' }}>VS</div>

            {/* Right Card: PrintSheba AI Smart Tool */}
            <div
              style={{
                flex: 1.2,
                padding: '36px',
                borderRadius: 24,
                backgroundImage: 'linear-gradient(135deg, rgba(99,102,241,0.2) 0%, rgba(16,185,129,0.15) 100%)',
                border: '2px solid #6366f1',
                boxShadow: '0 20px 50px rgba(99,102,241,0.25)',
                display: 'flex',
                flexDirection: 'column',
                gap: 18,
              }}
            >
              <div style={{ display: 'inline-flex', padding: '4px 12px', borderRadius: 8, backgroundColor: '#10b981', color: '#000000', fontSize: 12, fontWeight: 900, width: 'fit-content' }}>
                ⚡ PrintSheba Smart NID Tool
              </div>
              <h3 style={{ fontSize: 24, fontWeight: 900, margin: 0, color: '#ffffff' }}>
                মাত্র ৩০ সেকেন্ডে পারফেক্ট A4 প্রিন্ট!
              </h3>
              <div style={{ display: 'flex', flexDirection: 'column', gap: 12, fontSize: 14, color: '#e0e7ff', fontWeight: 600 }}>
                <div style={{ display: 'flex', alignItems: 'center', gap: 8 }}>
                  <span style={{ color: '#34d399', fontWeight: 900 }}>✓</span>
                  <span>স্বয়ংক্রিয় পার্সপেক্টিভ সংশোধন ও নিখুঁত ক্রপ</span>
                </div>
                <div style={{ display: 'flex', alignItems: 'center', gap: 8 }}>
                  <span style={{ color: '#34d399', fontWeight: 900 }}>✓</span>
                  <span>অফিসিয়াল NID অনুপাত ও কাটিং বর্ডার সংযুক্ত</span>
                </div>
                <div style={{ display: 'flex', alignItems: 'center', gap: 8 }}>
                  <span style={{ color: '#34d399', fontWeight: 900 }}>✓</span>
                  <span>এক ক্লিকে সরাসরি প্রিন্ট— কোনো ফটোশপ লাগবে না</span>
                </div>
              </div>

              <div
                style={{
                  marginTop: 10,
                  padding: '12px 18px',
                  borderRadius: 14,
                  backgroundColor: '#6366f1',
                  color: '#ffffff',
                  fontSize: 13,
                  fontWeight: 900,
                  textAlign: 'center',
                }}
              >
                🚀 দোকানের সময় বাঁচান, আয় বাড়ান!
              </div>
            </div>
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
              backgroundColor: '#6366f1',
              color: '#ffffff',
              fontSize: 14,
              fontWeight: 900,
              boxShadow: '0 6px 16px rgba(99,102,241,0.3)',
            }}
          >
            {stepTitle}
          </div>
          <span style={{ fontSize: 16, color: '#e2e8f0', fontWeight: 600 }}>
            {subtitle}
          </span>
        </div>

        <div style={{ fontSize: 13, color: '#64748b', fontWeight: 700 }}>
          PrintSheba.com • স্মার্ট ও ঝামেলাহীন প্রিন্টিং সল্যুশন
        </div>
      </div>
    </AbsoluteFill>
  );
};
