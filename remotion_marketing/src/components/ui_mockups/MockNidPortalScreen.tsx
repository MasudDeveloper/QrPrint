import React from 'react';

export interface NidPortalScreenProps {
  step?: 'upload_front' | 'upload_back' | 'preview_a4' | 'printing';
  frontCaptured?: boolean;
  backCaptured?: boolean;
  copies?: number;
}

export const MockNidPortalScreen: React.FC<NidPortalScreenProps> = ({
  step = 'preview_a4',
  frontCaptured = true,
  backCaptured = true,
  copies = 1,
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
          <span>https://printsheba.com/customer/office-nid-upload</span>
        </div>
      </div>

      {/* Main Content Area */}
      <div style={{ flex: 1, padding: '20px 36px', overflowY: 'hidden', display: 'flex', gap: 30 }}>
        {/* Left Control & Capture Panel */}
        <div style={{ flex: 1.1, display: 'flex', flexDirection: 'column', gap: 14 }}>
          <div>
            <div style={{ display: 'inline-flex', alignItems: 'center', gap: 6, padding: '3px 12px', borderRadius: 9999, backgroundColor: 'rgba(99,102,241,0.15)', border: '1px solid rgba(99,102,241,0.3)', color: '#818cf8', fontSize: 11, fontWeight: 800, textTransform: 'uppercase' }}>
              <span>🪪</span> AI Smart NID Generator
            </div>
            <h1 style={{ fontSize: 24, fontWeight: 900, margin: '6px 0 0 0' }}>
              স্মার্ট এনআইডি ও ভোটার কার্ড প্রিন্ট টুল
            </h1>
            <p style={{ fontSize: 12, color: '#94a3b8', margin: '3px 0 0 0' }}>
              মোবাইলের ক্যামেরা দিয়ে কার্ডের ছবি তুলুন— আমাদের এআই নিজে নিজেই ছবি সোজা ও ক্রপ করে A4 পেজে নিখুঁত মাপে বসিয়ে দেবে!
            </p>
          </div>

          {/* Capture Boxes: Front & Back */}
          <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 14 }}>
            {/* Front Card */}
            <div
              style={{
                borderRadius: 16,
                backgroundColor: '#0f172a',
                border: frontCaptured ? '1.5px solid #10b981' : '1.5px dashed #475569',
                padding: '14px',
                display: 'flex',
                flexDirection: 'column',
                gap: 8,
                position: 'relative',
              }}
            >
              <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
                <span style={{ fontSize: 12, fontWeight: 800, color: '#cbd5e1' }}>১. সামনের পিঠ (Front)</span>
                {frontCaptured && (
                  <span style={{ fontSize: 10, fontWeight: 800, color: '#10b981', backgroundColor: 'rgba(16,185,129,0.2)', padding: '2px 6px', borderRadius: 6 }}>
                    ✓ কমপ্লিট
                  </span>
                )}
              </div>

              <div
                style={{
                  height: 90,
                  borderRadius: 10,
                  backgroundColor: '#020617',
                  border: '1px solid #1e293b',
                  display: 'flex',
                  alignItems: 'center',
                  justifyContent: 'center',
                  position: 'relative',
                  overflow: 'hidden',
                }}
              >
                {frontCaptured ? (
                  <div style={{ width: '85%', height: '80%', backgroundColor: '#0284c7', borderRadius: 8, padding: 8, color: '#fff', display: 'flex', gap: 8, alignItems: 'center', border: '1px solid #38bdf8' }}>
                    <div style={{ width: 26, height: 32, backgroundColor: '#bae6fd', borderRadius: 4, display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 14 }}>👤</div>
                    <div style={{ display: 'flex', flexDirection: 'column', gap: 2, flex: 1 }}>
                      <div style={{ height: 5, width: '80%', backgroundColor: '#ffffff', borderRadius: 2 }} />
                      <div style={{ height: 4, width: '60%', backgroundColor: '#e0f2fe', borderRadius: 2 }} />
                      <div style={{ height: 4, width: '40%', backgroundColor: '#e0f2fe', borderRadius: 2 }} />
                    </div>
                  </div>
                ) : (
                  <span style={{ fontSize: 11, color: '#64748b' }}>📷 ছবি তুলুন বা আপলোড</span>
                )}
              </div>
            </div>

            {/* Back Card */}
            <div
              style={{
                borderRadius: 16,
                backgroundColor: '#0f172a',
                border: backCaptured ? '1.5px solid #10b981' : '1.5px dashed #475569',
                padding: '14px',
                display: 'flex',
                flexDirection: 'column',
                gap: 8,
                position: 'relative',
              }}
            >
              <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
                <span style={{ fontSize: 12, fontWeight: 800, color: '#cbd5e1' }}>২. পেছনের পিঠ (Back)</span>
                {backCaptured && (
                  <span style={{ fontSize: 10, fontWeight: 800, color: '#10b981', backgroundColor: 'rgba(16,185,129,0.2)', padding: '2px 6px', borderRadius: 6 }}>
                    ✓ কমপ্লিট
                  </span>
                )}
              </div>

              <div
                style={{
                  height: 90,
                  borderRadius: 10,
                  backgroundColor: '#020617',
                  border: '1px solid #1e293b',
                  display: 'flex',
                  alignItems: 'center',
                  justifyContent: 'center',
                  position: 'relative',
                  overflow: 'hidden',
                }}
              >
                {backCaptured ? (
                  <div style={{ width: '85%', height: '80%', backgroundColor: '#0284c7', borderRadius: 8, padding: 8, color: '#fff', display: 'flex', flexDirection: 'column', gap: 4, justifyContent: 'center', border: '1px solid #38bdf8' }}>
                    <div style={{ height: 4, width: '90%', backgroundColor: '#ffffff', borderRadius: 2 }} />
                    <div style={{ height: 4, width: '75%', backgroundColor: '#e0f2fe', borderRadius: 2 }} />
                    <div style={{ height: 10, width: '100%', backgroundColor: '#0c4a6e', borderRadius: 2, display: 'flex', alignItems: 'center', padding: '0 4px' }}>
                      <span style={{ fontSize: 7, fontFamily: 'monospace', color: '#38bdf8' }}>||| | |||| | ||||| ||</span>
                    </div>
                  </div>
                ) : (
                  <span style={{ fontSize: 11, color: '#64748b' }}>📷 ছবি তুলুন বা আপলোড</span>
                )}
              </div>
            </div>
          </div>

          {/* AI Straighten & Edge Detection Features */}
          <div style={{ padding: '12px 16px', borderRadius: 14, backgroundColor: '#0f172a', border: '1px solid #1e293b', display: 'flex', flexDirection: 'column', gap: 6 }}>
            <span style={{ fontSize: 11, fontWeight: 800, color: '#10b981' }}>⚡ অটো-প্রসেসিং সক্রিয়:</span>
            <div style={{ display: 'flex', gap: 10, fontSize: 11, color: '#94a3b8' }}>
              <span>✓ পার্সপেক্টিভ সোজা করা</span>
              <span>✓ ব্যাকগ্রাউন্ড ক্রপ</span>
              <span>✓ অফিসিয়াল অনুপাত (85.6mm × 54mm)</span>
            </div>
          </div>

          {/* One Click Print Action Button */}
          <div style={{ marginTop: 'auto', display: 'flex', gap: 12 }}>
            <div
              style={{
                flex: 1,
                padding: '14px',
                borderRadius: 14,
                backgroundImage: 'linear-gradient(135deg, #6366f1 0%, #4f46e5 100%)',
                color: '#ffffff',
                fontSize: 14,
                fontWeight: 900,
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'center',
                gap: 8,
                boxShadow: '0 10px 25px rgba(99,102,241,0.35)',
              }}
            >
              <span>🖨️</span> {step === 'printing' ? 'প্রিন্টারে ফাইল পাঠানো হচ্ছে...' : 'সরাসরি অফিস প্রিন্ট করুন (ফ্রি)'}
            </div>
          </div>
        </div>

        {/* Right Panel: High-Fidelity A4 Page Preview Mockup */}
        <div style={{ flex: 0.9, display: 'flex', flexDirection: 'column', alignItems: 'center', justifyContent: 'center' }}>
          <div style={{ fontSize: 11, fontWeight: 800, color: '#94a3b8', marginBottom: 8, textTransform: 'uppercase', letterSpacing: '0.05em' }}>
            A4 পেপারে লাইভ প্রিভিউ (অফিসিয়াল প্লেসমেন্ট)
          </div>

          {/* A4 Sheet White Card */}
          <div
            style={{
              width: 320,
              height: 452,
              backgroundColor: '#ffffff',
              borderRadius: 8,
              boxShadow: '0 20px 45px rgba(0,0,0,0.8), 0 0 0 1px rgba(255,255,255,0.1)',
              padding: '24px 20px',
              display: 'flex',
              flexDirection: 'column',
              gap: 24,
              alignItems: 'center',
              position: 'relative',
            }}
          >
            {/* Top Sheet Header */}
            <div style={{ width: '100%', borderBottom: '1px solid #f1f5f9', paddingBottom: 6, display: 'flex', justifyContent: 'space-between', fontSize: 9, color: '#94a3b8' }}>
              <span>জাতীয় পরিচয়পত্র (NID) কপি</span>
              <span>স্কেল: ১০০% (অরিজিনাল সাইজ)</span>
            </div>

            {/* Front Card Placement on A4 */}
            <div
              style={{
                width: 220,
                height: 138,
                borderRadius: 8,
                backgroundColor: '#0284c7',
                border: '1.5px solid #0369a1',
                boxShadow: '0 4px 12px rgba(0,0,0,0.15)',
                padding: 10,
                color: '#fff',
                display: 'flex',
                gap: 10,
                position: 'relative',
              }}
            >
              <div style={{ width: 44, height: 56, backgroundColor: '#e0f2fe', borderRadius: 6, display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 24, border: '1px solid #bae6fd' }}>
                👤
              </div>
              <div style={{ flex: 1, display: 'flex', flexDirection: 'column', gap: 4 }}>
                <span style={{ fontSize: 9, fontWeight: 900, color: '#e0f2fe' }}>গণপ্রজাতন্ত্রী বাংলাদেশ সরকার</span>
                <span style={{ fontSize: 8, fontWeight: 700, color: '#ffffff' }}>National ID Card</span>
                <div style={{ height: 6, width: '90%', backgroundColor: '#ffffff', borderRadius: 3, marginTop: 4 }} />
                <div style={{ height: 5, width: '70%', backgroundColor: '#bae6fd', borderRadius: 3 }} />
                <div style={{ height: 5, width: '50%', backgroundColor: '#bae6fd', borderRadius: 3 }} />
                <span style={{ fontSize: 8, fontFamily: 'monospace', color: '#fef08a', marginTop: 'auto', fontWeight: 900 }}>
                  NID: 19892691234567890
                </span>
              </div>
            </div>

            {/* Back Card Placement on A4 */}
            <div
              style={{
                width: 220,
                height: 138,
                borderRadius: 8,
                backgroundColor: '#0284c7',
                border: '1.5px solid #0369a1',
                boxShadow: '0 4px 12px rgba(0,0,0,0.15)',
                padding: 10,
                color: '#fff',
                display: 'flex',
                flexDirection: 'column',
                gap: 6,
                position: 'relative',
              }}
            >
              <span style={{ fontSize: 8, color: '#e0f2fe' }}>ঠিকানা: বাসা/হোল্ডিং: ১২, রোড: ৪, ধানমন্ডি, ঢাকা</span>
              <div style={{ height: 5, width: '85%', backgroundColor: '#ffffff', borderRadius: 3 }} />
              <div style={{ height: 5, width: '65%', backgroundColor: '#bae6fd', borderRadius: 3 }} />
              <div style={{ marginTop: 'auto', height: 18, width: '100%', backgroundColor: '#075985', borderRadius: 4, display: 'flex', alignItems: 'center', padding: '0 8px', justifyContent: 'space-between' }}>
                <span style={{ fontSize: 9, fontFamily: 'monospace', color: '#7dd3fc', letterSpacing: '0.1em' }}>
                  &lt;&lt;BGD1989269123&lt;&lt;8902148M&lt;&lt;&lt;&lt;&lt;&lt;
                </span>
              </div>
            </div>

            {/* Cut Line Marker */}
            <div style={{ position: 'absolute', bottom: 12, fontSize: 8, color: '#cbd5e1' }}>
              ✂️ কাটিং বর্ডার স্বয়ংক্রিয়ভাবে সংবলিত
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};
