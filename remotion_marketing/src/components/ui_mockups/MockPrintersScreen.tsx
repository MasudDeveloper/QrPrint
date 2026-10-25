import React from 'react';

export interface PrintersScreenProps {
  hasAddedColor?: boolean;
  highlightPrinter?: string;
}

export const MockPrintersScreen: React.FC<PrintersScreenProps> = ({
  hasAddedColor = true,
  highlightPrinter,
}) => {
  return (
    <div
      style={{
        width: 1280,
        height: 720,
        backgroundColor: '#070b14',
        borderRadius: 24,
        border: '2px solid #1e293b',
        boxShadow: '0 25px 60px -15px rgba(0,0,0,0.8), 0 0 0 1px rgba(255,255,255,0.08)',
        overflow: 'hidden',
        display: 'flex',
        flexDirection: 'column',
        fontFamily: "'Hind Siliguri', sans-serif",
        color: '#ffffff',
      }}
    >
      {/* Top Browser Bar */}
      <div
        style={{
          height: 44,
          backgroundColor: '#0b1120',
          borderBottom: '1px solid #1e293b',
          display: 'flex',
          alignItems: 'center',
          padding: '0 18px',
          gap: 16,
        }}
      >
        <div style={{ display: 'flex', gap: 7 }}>
          <div style={{ width: 12, height: 12, borderRadius: '50%', backgroundColor: '#ef4444' }} />
          <div style={{ width: 12, height: 12, borderRadius: '50%', backgroundColor: '#f59e0b' }} />
          <div style={{ width: 12, height: 12, borderRadius: '50%', backgroundColor: '#10b981' }} />
        </div>
        <div
          style={{
            flex: 1,
            maxWidth: 620,
            margin: '0 auto',
            height: 28,
            backgroundColor: '#020617',
            borderRadius: 8,
            border: '1px solid #1e293b',
            display: 'flex',
            alignItems: 'center',
            padding: '0 12px',
            fontSize: 12,
            color: '#94a3b8',
            fontFamily: "'Plus Jakarta Sans', monospace",
            gap: 8,
          }}
        >
          <span style={{ color: '#10b981' }}>🔒</span>
          <span>https://printsheba.com/shop/printers</span>
        </div>
      </div>

      {/* Main Content */}
      <div style={{ flex: 1, padding: '24px 36px', overflowY: 'hidden', display: 'flex', flexDirection: 'column', gap: 18 }}>
        <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
          <div>
            <h1 style={{ fontSize: 24, fontWeight: 900, margin: 0, display: 'flex', alignItems: 'center', gap: 8 }}>
              <span>🖨️</span> সংযুক্ত প্রিন্টারসমূহ ও স্মার্ট রাউটিং
            </h1>
            <p style={{ fontSize: 13, color: '#94a3b8', margin: '4px 0 0 0' }}>
              দোকানের প্রিন্টারগুলোর নাম ও অটো-রাউটিং (সাদাকালো vs কালার) কনফিগার করুন
            </p>
          </div>
          <div style={{ display: 'flex', gap: 10 }}>
            <div style={{ padding: '8px 16px', borderRadius: 12, backgroundColor: '#1e293b', border: '1px solid #334155', fontSize: 12, fontWeight: 800, color: '#cbd5e1' }}>
              🔄 রিফ্রেশ প্রিন্টার লিস্ট
            </div>
            <div style={{ padding: '8px 18px', borderRadius: 12, backgroundColor: '#f43f5e', fontSize: 12, fontWeight: 900, color: '#ffffff' }}>
              + নতুন প্রিন্টার যোগ করুন
            </div>
          </div>
        </div>

        {/* Live sync banner */}
        <div
          style={{
            padding: '12px 20px',
            borderRadius: 16,
            backgroundColor: 'rgba(16,185,129,0.1)',
            border: '1px solid rgba(16,185,129,0.25)',
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'space-between',
          }}
        >
          <div style={{ display: 'flex', alignItems: 'center', gap: 12 }}>
            <span style={{ width: 10, height: 10, borderRadius: '50%', backgroundColor: '#10b981', boxShadow: '0 0 8px #10b981' }} />
            <div>
              <span style={{ fontSize: 13, fontWeight: 800, color: '#6ee7b7' }}>
                উইন্ডোজ ক্লাউড এজেন্ট অটো-সিঙ্ক সচল (v2.6 Native Sync)
              </span>
              <p style={{ fontSize: 11, color: '#34d399', margin: '2px 0 0 0' }}>
                আপনার কম্পিউটারে PrintShebaAgent.exe চালু থাকলে প্রিন্টার স্বয়ংক্রিয়ভাবে ডিটেক্ট হবে।
              </p>
            </div>
          </div>
          <span style={{ fontSize: 11, fontWeight: 800, color: '#10b981', backgroundColor: 'rgba(16,185,129,0.2)', padding: '4px 10px', borderRadius: 8 }}>
            ২টি প্রিন্টার রেডি
          </span>
        </div>

        {/* Printer Cards Grid */}
        <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 20 }}>
          {/* Printer 1: HP LaserJet Pro (B&W) */}
          <div
            style={{
              padding: '20px 24px',
              borderRadius: 20,
              backgroundColor: '#0f172a',
              border: highlightPrinter === 'bw' ? '2px solid #10b981' : '1.5px solid #1e293b',
              boxShadow: highlightPrinter === 'bw' ? '0 0 20px rgba(16,185,129,0.2)' : 'none',
              display: 'flex',
              flexDirection: 'column',
              gap: 14,
            }}
          >
            <div style={{ display: 'flex', alignItems: 'center', gap: 14 }}>
              <div style={{ width: 52, height: 52, borderRadius: 16, backgroundColor: 'rgba(16,185,129,0.15)', border: '1px solid rgba(16,185,129,0.3)', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 26 }}>
                🖨️
              </div>
              <div style={{ flex: 1 }}>
                <div style={{ display: 'flex', alignItems: 'center', gap: 8 }}>
                  <h3 style={{ fontSize: 16, fontWeight: 900, margin: 0 }}>HP LaserJet Pro MFP M428fdw</h3>
                  <span style={{ fontSize: 10, fontWeight: 800, padding: '2px 8px', borderRadius: 6, backgroundColor: 'rgba(244,63,94,0.2)', color: '#f43f5e', border: '1px solid rgba(244,63,94,0.3)' }}>
                    ডিফল্ট প্রিন্টার
                  </span>
                </div>
                <div style={{ fontSize: 11, color: '#94a3b8', marginTop: 4 }}>
                  কাগজ সাইজ: A4, Legal | মোড: সাদাকালো হাই-স্পিড (40 PPM)
                </div>
              </div>
            </div>

            <div style={{ padding: '10px 14px', borderRadius: 12, backgroundColor: '#020617', border: '1px solid #1e293b', display: 'flex', justifyContent: 'space-between', alignItems: 'center', fontSize: 12 }}>
              <span style={{ color: '#94a3b8' }}>অটো-রাউটিং নীতি:</span>
              <span style={{ color: '#10b981', fontWeight: 800 }}>✓ সকল সাদাকালো ও সাধারণ ডকুমেন্টস</span>
            </div>

            <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
              <span style={{ fontSize: 12, color: '#10b981', fontWeight: 800, display: 'flex', alignItems: 'center', gap: 6 }}>
                <span style={{ width: 8, height: 8, borderRadius: '50%', backgroundColor: '#10b981' }} /> অনলাইন ও প্রস্তুত
              </span>
              <span style={{ fontSize: 11, color: '#64748b' }}>সর্বশেষ সিঙ্ক: এইমাত্র</span>
            </div>
          </div>

          {/* Printer 2: Epson EcoTank L8050 (Color) */}
          <div
            style={{
              padding: '20px 24px',
              borderRadius: 20,
              backgroundColor: '#0f172a',
              border: highlightPrinter === 'color' ? '2px solid #ec4899' : '1.5px solid #1e293b',
              boxShadow: highlightPrinter === 'color' ? '0 0 20px rgba(236,72,153,0.2)' : 'none',
              display: 'flex',
              flexDirection: 'column',
              gap: 14,
            }}
          >
            <div style={{ display: 'flex', alignItems: 'center', gap: 14 }}>
              <div style={{ width: 52, height: 52, borderRadius: 16, backgroundColor: 'rgba(236,72,153,0.15)', border: '1px solid rgba(236,72,153,0.3)', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 26 }}>
                🎨
              </div>
              <div style={{ flex: 1 }}>
                <div style={{ display: 'flex', alignItems: 'center', gap: 8 }}>
                  <h3 style={{ fontSize: 16, fontWeight: 900, margin: 0 }}>Epson EcoTank L8050 Photo</h3>
                  <span style={{ fontSize: 10, fontWeight: 800, padding: '2px 8px', borderRadius: 6, backgroundColor: 'rgba(236,72,153,0.2)', color: '#ec4899', border: '1px solid rgba(236,72,153,0.3)' }}>
                    কালার ডেডিকেটেড
                  </span>
                </div>
                <div style={{ fontSize: 11, color: '#94a3b8', marginTop: 4 }}>
                  কাগজ সাইজ: A4, Photo Paper | মোড: ৬-কালার ভাইব্রেন্ট ফটো
                </div>
              </div>
            </div>

            <div style={{ padding: '10px 14px', borderRadius: 12, backgroundColor: '#020617', border: '1px solid #1e293b', display: 'flex', justifyContent: 'space-between', alignItems: 'center', fontSize: 12 }}>
              <span style={{ color: '#94a3b8' }}>অটো-রাউটিং নীতি:</span>
              <span style={{ color: '#ec4899', fontWeight: 800 }}>✓ কাস্টমার কালার অর্ডার দিলে অটো রাউট</span>
            </div>

            <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
              <span style={{ fontSize: 12, color: '#10b981', fontWeight: 800, display: 'flex', alignItems: 'center', gap: 6 }}>
                <span style={{ width: 8, height: 8, borderRadius: '50%', backgroundColor: '#10b981' }} /> অনলাইন ও প্রস্তুত
              </span>
              <span style={{ fontSize: 11, color: '#64748b' }}>স্মার্ট অটো-সুইচ অন</span>
            </div>
          </div>
        </div>

        {/* Smart routing info card */}
        <div style={{ padding: '14px 20px', borderRadius: 16, backgroundColor: '#020617', border: '1px solid #1e293b', display: 'flex', alignItems: 'center', gap: 14 }}>
          <span style={{ fontSize: 22 }}>⚡</span>
          <div style={{ fontSize: 12, color: '#94a3b8' }}>
            <strong style={{ color: '#fff' }}>স্মার্ট অটো-রাউটিং ফিচার:</strong> কাস্টমার মোবাইল থেকে কালার সিলেক্ট করলে সিস্টেম স্বয়ংক্রিয়ভাবে Epson কালার প্রিন্টারে পাঠাবে, আর সাধারণ প্রিন্ট দিলে HP সাদাকালো প্রিন্টারে পাঠাবে— দোকানদারের কোনো ফাইল ঘাঁটাঘাঁটি বা ম্যানুয়াল সিলেকশনের দরকার নেই!
          </div>
        </div>
      </div>
    </div>
  );
};
