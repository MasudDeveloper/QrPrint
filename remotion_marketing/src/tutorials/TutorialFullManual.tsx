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
import { MockQrPosterScreen } from '../components/ui_mockups/MockQrPosterScreen';
import { MockCustomerMobileScreen } from '../components/ui_mockups/MockCustomerMobileScreen';
import { MockOfficeDirectPrintScreen } from '../components/ui_mockups/MockOfficeDirectPrintScreen';
import { MockShopDashboardScreen } from '../components/ui_mockups/MockShopDashboardScreen';
import { MockCursor } from '../components/ui_mockups/MockCursor';

export const TutorialFullManual: React.FC = () => {
  const frame = useCurrentFrame();
  const { fps } = useVideoConfig();

  // Total 6000 frames (200.0s = 3m 20s @ 30 FPS)
  // Chapter 1: 0 - 800     (0.0s - 26.6s)   -> পরিচিতি ও প্ল্যাটফর্ম ওভারভিউ
  // Chapter 2: 800 - 1600   (26.6s - 53.3s)  -> দোকান ও অফিস একাউন্ট রেজিস্ট্রেশন
  // Chapter 3: 1600 - 2500  (53.3s - 83.3s)  -> রেট সেটিংস, ফটোকপিয়ার ও ওয়ালেট কনফিগারেশন
  // Chapter 4: 2500 - 3400  (83.3s - 113.3s) -> উইন্ডোজ অটো-প্রিন্ট ক্লাউড এজেন্ট v2.7 সেটআপ
  // Chapter 5: 3400 - 4300  (113.3s - 143.3s)-> কাউন্টার QR পোস্টার ও কাস্টমার ১-ক্লিক মোবাইল প্রিন্ট
  // Chapter 6: 4300 - 5100  (143.3s - 170.0s)-> ইনস্ট্যান্ট শেয়ার প্রিন্ট ও স্মার্ট এআই স্ক্যানার
  // Chapter 7: 5100 - 6000  (170.0s - 200.0s)-> লাইভ কিউ, ক্যাশ অনুমোদন ও সম্পূর্ণ রিপোর্ট অডিট

  let activeChapter = 1;
  let chapterTitle = 'অধ্যায় ১: প্রিন্টসেবা (PrintSheba) কী ও কীভাবে কাজ করে?';
  let subtitle = 'পেনড্রাইভ, ব্লুটুথ ও হোয়াটসঅ্যাপের দিন শেষ! কাস্টমার QR স্ক্যান করলেই স্বয়ংক্রিয় ক্লাউড প্রিন্ট।';
  let badgeText = 'OVERVIEW & ARCHITECTURE';

  if (frame >= 800 && frame < 1600) {
    activeChapter = 2;
    chapterTitle = 'অধ্যায় ২: দোকানদার বনাম কর্পোরেট অফিস মোড রেজিস্ট্রেশন';
    subtitle = 'সাইবার ক্যাফে বা প্রিন্ট শপের জন্য পেমেন্ট মোড এবং অফিসের জন্য জিরো-পেমেন্ট কর্মী ট্র্যাকিং মোড বেছে নিন।';
    badgeText = 'DUAL MODE REGISTRATION';
  } else if (frame >= 1600 && frame < 2500) {
    activeChapter = 3;
    chapterTitle = 'অধ্যায় ৩: রেট সেটিংস, ওয়ালেট ও ফটোকপি মেশিন মোড';
    subtitle = 'সাদাকালো লেজার প্রিন্টার বনাম কম খরচে বড় ফটোকপিয়ার মেশিনের রেট এবং নিজস্ব বিকাশ/নগদ ওয়ালেট যুক্ত করুন।';
    badgeText = 'RATES & WALLET CONFIG';
  } else if (frame >= 2500 && frame < 3400) {
    activeChapter = 4;
    chapterTitle = 'অধ্যায় ৪: উইন্ডোজ ক্লাউড এজেন্ট v2.7 সেটআপ ও টেস্ট প্রিন্ট';
    subtitle = 'PrintShebaAgent.exe ফাইলটি চালু করুন, প্রিন্টার ম্যাপিং করুন ও ব্যাকগ্রাউন্ড সিস্টেম ট্রেতে সচল রাখুন।';
    badgeText = 'WINDOWS CLOUD AGENT v2.7';
  } else if (frame >= 3400 && frame < 4300) {
    activeChapter = 5;
    chapterTitle = 'অধ্যায় ৫: কাউন্টার QR পোস্টার ও কাস্টমার সেলফ-সার্ভিস প্রিন্ট';
    subtitle = 'কাউন্টারে পোস্টার টানিয়ে দিন— কাস্টমার মোবাইলে স্ক্যান করে পেজ কাউন্ট ও পেমেন্ট করলেই প্রিন্ট বের হবে!';
    badgeText = 'CUSTOMER QR SELF-SERVICE';
  } else if (frame >= 4300 && frame < 5100) {
    activeChapter = 6;
    chapterTitle = 'অধ্যায় ৬: ইনস্ট্যান্ট ও হোয়াটসঅ্যাপ শেয়ার প্রিন্ট + এআই স্ক্যানার';
    subtitle = 'গ্যালারি বা হোয়াটসঅ্যাপ থেকে ১-ট্যাপে সরাসরি প্রিন্ট দিন এবং স্মার্ট এআই দিয়ে ছবি স্বয়ংক্রিয় সোজা করুন।';
    badgeText = 'INSTANT SHARE & AI SCANNER';
  } else if (frame >= 5100) {
    activeChapter = 7;
    chapterTitle = 'অধ্যায় ৭: লাইভ কিউ, ক্যাশ অনুমোদন ও সম্পূর্ণ অডিট রিপোর্ট';
    subtitle = 'রিয়েল-টাইম প্রিন্ট কিউ মনিটর করুন, কাউন্টারে ক্যাশ অনুমোদন দিন এবং দৈনিক আয় ও কাগজের হিসাব এক্সপোর্ট করুন।';
    badgeText = 'LIVE QUEUE & AUDIT REPORTS';
  }

  // Interactive mouse pointer animation
  let showCursor = false;
  let cursorX = 960;
  let cursorY = 540;
  let isClicking = false;
  let cursorLabel = '';

  if (frame >= 500 && frame < 720) {
    // Chapter 1: Click "বিনামূল্যে শুরু করুন"
    showCursor = true;
    cursorLabel = '🚀 ফ্রি শুরু করুন';
    cursorX = interpolate(frame, [500, 580], [920, 1140], { extrapolateRight: 'clamp' });
    cursorY = interpolate(frame, [500, 580], [450, 530], { extrapolateRight: 'clamp' });
    if (frame >= 580 && frame < 620) isClicking = true;
  } else if (frame >= 1350 && frame < 1550) {
    // Chapter 2: Click "রেজিস্ট্রেশন করুন"
    showCursor = true;
    cursorLabel = '✓ একাউন্ট তৈরি করুন';
    cursorX = interpolate(frame, [1350, 1420], [900, 960], { extrapolateRight: 'clamp' });
    cursorY = interpolate(frame, [1350, 1420], [530, 610], { extrapolateRight: 'clamp' });
    if (frame >= 1420 && frame < 1460) isClicking = true;
  } else if (frame >= 2250 && frame < 2450) {
    // Chapter 3: Click "সেটিংস সেভ করুন"
    showCursor = true;
    cursorLabel = '💾 রেট সংরক্ষণ করুন';
    cursorX = interpolate(frame, [2250, 2320], [960, 1160], { extrapolateRight: 'clamp' });
    cursorY = interpolate(frame, [2250, 2320], [550, 640], { extrapolateRight: 'clamp' });
    if (frame >= 2320 && frame < 2360) isClicking = true;
  } else if (frame >= 3050 && frame < 3280) {
    // Chapter 4: Click "টেস্ট পেজ প্রিন্ট" on Windows Agent
    showCursor = true;
    cursorLabel = '🖨️ টেস্ট পেজ পাঠান';
    cursorX = interpolate(frame, [3050, 3120], [800, 950], { extrapolateRight: 'clamp' });
    cursorY = interpolate(frame, [3050, 3120], [450, 520], { extrapolateRight: 'clamp' });
    if (frame >= 3120 && frame < 3160) isClicking = true;
  } else if (frame >= 4050 && frame < 4260) {
    // Chapter 5: Mobile customer tap "প্রিন্ট ও পেমেন্ট করুন"
    showCursor = true;
    cursorLabel = '💳 বিকাশ পেমেন্ট ও অটো প্রিন্ট';
    cursorX = interpolate(frame, [4050, 4120], [900, 960], { extrapolateRight: 'clamp' });
    cursorY = interpolate(frame, [4050, 4120], [680, 770], { extrapolateRight: 'clamp' });
    if (frame >= 4120 && frame < 4160) isClicking = true;
  } else if (frame >= 4850 && frame < 5050) {
    // Chapter 6: Click "এআই কর্নার ডিটেক্ট"
    showCursor = true;
    cursorLabel = '✨ AI সোজা করুন';
    cursorX = interpolate(frame, [4850, 4920], [850, 960], { extrapolateRight: 'clamp' });
    cursorY = interpolate(frame, [4850, 4920], [400, 470], { extrapolateRight: 'clamp' });
    if (frame >= 4920 && frame < 4960) isClicking = true;
  } else if (frame >= 5650 && frame < 5850) {
    // Chapter 7: Click "ক্যাশ অনুমোদন" on Dashboard
    showCursor = true;
    cursorLabel = '✓ ক্যাশ অনুমোদন করুন';
    cursorX = interpolate(frame, [5650, 5720], [1000, 1160], { extrapolateRight: 'clamp' });
    cursorY = interpolate(frame, [5650, 5720], [450, 520], { extrapolateRight: 'clamp' });
    if (frame >= 5720 && frame < 5760) isClicking = true;
  }

  return (
    <AbsoluteFill
      style={{
        backgroundColor: '#030712',
        color: '#ffffff',
        fontFamily: "'Hind Siliguri', 'Segoe UI', sans-serif",
        overflow: 'hidden',
      }}
    >
      {/* Optional Audio Track */}
      <Audio
        src={staticFile('audio/Voiceover_Tutorial_Shopkeeper_Full.mp3')}
        volume={0.85}
        startFrom={0}
        endAt={6000}
      />

      {/* Background Ambient Glow */}
      <div
        style={{
          position: 'absolute',
          top: -200,
          left: -200,
          width: 700,
          height: 700,
          borderRadius: '50%',
          backgroundColor: activeChapter % 2 === 0 ? 'rgba(56,189,248,0.08)' : 'rgba(225,29,72,0.1)',
          filter: 'blur(160px)',
          pointerEvents: 'none',
        }}
      />
      <div
        style={{
          position: 'absolute',
          bottom: -200,
          right: -200,
          width: 800,
          height: 800,
          borderRadius: '50%',
          backgroundColor: 'rgba(16,185,129,0.08)',
          filter: 'blur(180px)',
          pointerEvents: 'none',
        }}
      />

      {/* TOP HEADER & CHAPTER PROGRESS STEPPER */}
      <div
        style={{
          position: 'absolute',
          top: 0,
          left: 0,
          right: 0,
          height: 120,
          backgroundColor: 'rgba(11, 17, 32, 0.95)',
          borderBottom: '1.5px solid #1e293b',
          display: 'flex',
          flexDirection: 'column',
          justifyContent: 'space-between',
          padding: '16px 40px 14px 40px',
          zIndex: 100,
          boxShadow: '0 10px 30px rgba(0,0,0,0.5)',
        }}
      >
        {/* Row 1: Logo, Chapter Title & Badge */}
        <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
          <div style={{ display: 'flex', alignItems: 'center', gap: 14 }}>
            <div
              style={{
                width: 44,
                height: 44,
                borderRadius: 14,
                background: 'linear-gradient(135deg, #f43f5e, #e11d48)',
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'center',
                color: '#fff',
                fontSize: 22,
                fontWeight: 900,
                boxShadow: '0 6px 16px rgba(225,29,72,0.4)',
              }}
            >
              PS
            </div>
            <div>
              <div style={{ fontSize: 20, fontWeight: 900, lineHeight: 1.1 }}>
                Print<span style={{ color: '#f43f5e' }}>Sheba</span>
                <span style={{ fontSize: 13, color: '#94a3b8', fontWeight: 600, marginLeft: 10 }}>
                  | ওয়েবসাইট সম্পূর্ণ ব্যবহার নির্দেশিকা (Master Guide)
                </span>
              </div>
              <div style={{ fontSize: 13, color: '#e2e8f0', fontWeight: 800, marginTop: 4 }}>
                {chapterTitle}
              </div>
            </div>
          </div>

          <div style={{ display: 'flex', alignItems: 'center', gap: 12 }}>
            <div
              style={{
                padding: '4px 14px',
                borderRadius: 9999,
                backgroundColor: 'rgba(244,63,94,0.15)',
                border: '1px solid #f43f5e',
                color: '#f43f5e',
                fontSize: 11,
                fontWeight: 900,
                letterSpacing: '0.08em',
              }}
            >
              {badgeText}
            </div>
            <div
              style={{
                padding: '4px 12px',
                borderRadius: 9999,
                backgroundColor: '#1e293b',
                color: '#cbd5e1',
                fontSize: 12,
                fontWeight: 800,
              }}
            >
              অধ্যায় {activeChapter} / ৭
            </div>
          </div>
        </div>

        {/* Row 2: Stepper Progress Track */}
        <div style={{ display: 'flex', gap: 8, alignItems: 'center', marginTop: 8 }}>
          {[
            { num: 1, label: 'পরিচিতি' },
            { num: 2, label: 'রেজিস্ট্রেশন' },
            { num: 3, label: 'রেট ও ওয়ালেট' },
            { num: 4, label: 'উইন্ডোজ এজেন্ট' },
            { num: 5, label: 'QR পোস্টার' },
            { num: 6, label: 'শেয়ার প্রিন্ট & AI' },
            { num: 7, label: 'কিউ ও অডিট' },
          ].map((s) => {
            const isDone = s.num < activeChapter;
            const isCurrent = s.num === activeChapter;
            return (
              <div key={s.num} style={{ flex: 1, display: 'flex', flexDirection: 'column', gap: 3 }}>
                <div
                  style={{
                    height: 5,
                    borderRadius: 3,
                    backgroundColor: isCurrent ? '#f43f5e' : isDone ? '#10b981' : '#1e293b',
                    boxShadow: isCurrent ? '0 0 8px #f43f5e' : 'none',
                    transition: 'all 0.3s ease',
                  }}
                />
                <span
                  style={{
                    fontSize: 10,
                    fontWeight: isCurrent ? 800 : 600,
                    color: isCurrent ? '#f43f5e' : isDone ? '#10b981' : '#64748b',
                    whiteSpace: 'nowrap',
                    overflow: 'hidden',
                    textOverflow: 'ellipsis',
                  }}
                >
                  {s.num}. {s.label}
                </span>
              </div>
            );
          })}
        </div>
      </div>

      {/* MAIN SCREEN CANVAS AREA */}
      <div
        style={{
          position: 'absolute',
          top: 135,
          left: 0,
          right: 0,
          bottom: 70,
          display: 'flex',
          alignItems: 'center',
          justifyContent: 'center',
          padding: '0 40px',
        }}
      >
        {/* CHAPTER 1: Overview & How it Works */}
        {activeChapter === 1 && (
          <MockShopDashboardScreen
            activeTab="dashboard"
            highlightAction="overview"
            isApproving={false}
          />
        )}

        {/* CHAPTER 2: Registration (Shop vs Office) */}
        {activeChapter === 2 && (
          <MockRegisterScreen
            name="মোঃ মাসুদ রানা"
            shopName="গ্রিন সাইবার ক্যাফে ও ফটোকপি"
            phone="01712-345678"
            email="greencyber@printsheba.com"
            isSubmitting={frame >= 1420}
          />
        )}

        {/* CHAPTER 3: Rates, Photocopier & Wallets */}
        {activeChapter === 3 && (
          <MockRatesSetupScreen
            bwRate={3.0}
            photocopyRate={2.0}
            colorRate={10.0}
            duplexDiscount={1.0}
            bkashNumber="01712-345678"
            nagadNumber="01898-765432"
            rocketNumber="01911-223344"
            hasPhotocopier={true}
            hasColor={true}
            hasDuplex={true}
            isSaved={frame >= 2320}
          />
        )}

        {/* CHAPTER 4: Windows Cloud Agent v2.7 */}
        {activeChapter === 4 && (
          <MockAgentWindowModal
            status={frame >= 3120 ? '● ক্লাউড সিঙ্ক সক্রিয় (অনলাইন)' : '● সার্ভার সিঙ্ক অপেক্ষা...'}
            shopName="গ্রিন সাইবার ক্যাফে ও ফটোকপি"
            serverUrl="https://printsheba.com"
            apiToken="ps_live_89f02c4819e4a3b70e"
            bwPrinter="HP LaserJet Pro M404dn"
            colorPrinter="EPSON L3210 Series"
            photocopyPrinter="Toshiba e-Studio 2523A"
            isSaved={true}
            isTestPrinting={frame >= 3120 && frame < 3300}
            isMinimizedToTray={frame >= 3300}
          />
        )}

        {/* CHAPTER 5: QR Poster & Customer Mobile Flow */}
        {activeChapter === 5 && (
          <div style={{ display: 'flex', gap: 60, alignItems: 'center', justifyContent: 'center' }}>
            <div style={{ transform: 'scale(0.85)', transformOrigin: 'center' }}>
              <MockQrPosterScreen
                shopName="গ্রিন সাইবার ক্যাফে ও ফটোকপি"
                isPrinting={frame >= 3600}
              />
            </div>
            <div style={{ transform: 'scale(0.85)', transformOrigin: 'center' }}>
              <MockCustomerMobileScreen
                hasFile={true}
                fileName="Office_Document.pdf"
                totalPages={4}
                colorMode={frame >= 3800 ? 'photocopy' : 'bw'}
                copies={1}
                isDuplex={true}
                totalBill={frame >= 3800 ? 8 : 12}
                paymentMethod="bkash"
                isSubmitted={frame >= 4120}
                jobToken="PS-AUTO-8941"
              />
            </div>
          </div>
        )}

        {/* CHAPTER 6: Instant Quick Print & Smart AI Doc Scanner */}
        {activeChapter === 6 && (
          <MockOfficeDirectPrintScreen
            fileName="Scanned_NID_SmartCard.jpg"
            fileSize="320 KB (১টি পেজ)"
            printerName="Toshiba e-Studio 2523A (ফটোকপিয়ার)"
            copies={1}
            isPrinting={frame >= 4920}
            statusText="স্বয়ংক্রিয় এআই ক্রপ সম্পন্ন ও প্রিন্ট প্রস্তুত"
          />
        )}

        {/* CHAPTER 7: Live Queue, Cash Approval & Reports */}
        {activeChapter === 7 && (
          <MockShopDashboardScreen
            activeTab="dashboard"
            highlightAction="cash_approve"
            isApproving={frame >= 5720}
          />
        )}
      </div>

      {/* Interactive Cursor Pointer */}
      {showCursor && (
        <MockCursor
          x={cursorX}
          y={cursorY}
          isClicking={isClicking}
          label={cursorLabel}
        />
      )}

      {/* BOTTOM FOOTER SUBTITLE TICKER */}
      <div
        style={{
          position: 'absolute',
          bottom: 0,
          left: 0,
          right: 0,
          height: 60,
          backgroundColor: 'rgba(15, 23, 42, 0.95)',
          borderTop: '1px solid #1e293b',
          display: 'flex',
          alignItems: 'center',
          justifyContent: 'space-between',
          padding: '0 40px',
          zIndex: 100,
        }}
      >
        <div style={{ display: 'flex', alignItems: 'center', gap: 12 }}>
          <span style={{ fontSize: 16 }}>💡</span>
          <span style={{ fontSize: 13, color: '#f1f5f9', fontWeight: 700 }}>
            {subtitle}
          </span>
        </div>

        <div style={{ display: 'flex', alignItems: 'center', gap: 8 }}>
          <span style={{ fontSize: 11, color: '#94a3b8' }}>ওয়েবসাইট ভিজিট করুন:</span>
          <span
            style={{
              fontSize: 12,
              fontWeight: 900,
              color: '#38bdf8',
              fontFamily: "'Plus Jakarta Sans', monospace",
              backgroundColor: '#020617',
              padding: '3px 10px',
              borderRadius: 6,
              border: '1px solid #1e293b',
            }}
          >
            https://printsheba.com
          </span>
        </div>
      </div>
    </AbsoluteFill>
  );
};
