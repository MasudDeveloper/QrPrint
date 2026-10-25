import React from 'react';
import { Img, staticFile } from 'remotion';

export interface CustomerScreenState {
  hasFile?: boolean;
  fileName?: string;
  totalPages?: number;
  colorMode?: 'bw' | 'color';
  copies?: number;
  isDuplex?: boolean;
  totalBill?: number;
  paymentMethod?: 'bkash' | 'cash' | 'nagad';
  isSubmitted?: boolean;
  jobToken?: string;
}

export const MockCustomerMobileScreen: React.FC<CustomerScreenState> = ({
  hasFile = true,
  fileName = 'Proposal_Final.pdf',
  totalPages = 5,
  colorMode = 'color',
  copies = 2,
  isDuplex = true,
  totalBill = 30,
  paymentMethod = 'bkash',
  isSubmitted = false,
  jobToken = 'PS-89412',
}) => {
  return (
    <div
      style={{
        width: 440,
        height: 860,
        backgroundColor: '#f8fafc',
        borderRadius: 44,
        border: '10px solid #1e293b',
        boxShadow: '0 30px 60px -10px rgba(0,0,0,0.7), 0 0 0 1px rgba(255,255,255,0.1)',
        position: 'relative',
        overflow: 'hidden',
        display: 'flex',
        flexDirection: 'column',
        fontFamily: "'Hind Siliguri', sans-serif",
        color: '#1e293b',
      }}
    >
      {/* Phone Notch / Dynamic Island */}
      <div
        style={{
          position: 'absolute',
          top: 0,
          left: '50%',
          transform: 'translateX(-50%)',
          width: 140,
          height: 24,
          backgroundColor: '#1e293b',
          borderBottomLeftRadius: 16,
          borderBottomRightRadius: 16,
          zIndex: 50,
          display: 'flex',
          alignItems: 'center',
          justifyContent: 'center',
          gap: 8,
        }}
      >
        <div style={{ width: 10, height: 10, borderRadius: '50%', backgroundColor: '#0f172a' }} />
        <div style={{ width: 8, height: 8, borderRadius: '50%', backgroundColor: '#059669' }} />
      </div>

      {/* Mobile Top Status Bar */}
      <div
        style={{
          height: 38,
          padding: '0 24px',
          display: 'flex',
          alignItems: 'center',
          justifyContent: 'space-between',
          fontSize: 12,
          fontWeight: 700,
          color: '#64748b',
          backgroundColor: '#ffffff',
          borderBottom: '1px solid #f1f5f9',
          paddingTop: 4,
        }}
      >
        <span>9:41 AM</span>
        <div style={{ display: 'flex', gap: 6, alignItems: 'center' }}>
          <span>📶 5G</span>
          <span>🔋 100%</span>
        </div>
      </div>

      {/* PrintSheba Mobile Header */}
      <div
        style={{
          padding: '12px 18px',
          backgroundColor: '#ffffff',
          borderBottom: '1px solid #e2e8f0',
          display: 'flex',
          alignItems: 'center',
          justifyContent: 'space-between',
        }}
      >
        <div style={{ display: 'flex', alignItems: 'center', gap: 10 }}>
          <Img
            src={staticFile('logo.svg')}
            style={{ width: 36, height: 36, borderRadius: 10 }}
          />
          <div>
            <div style={{ fontSize: 14, fontWeight: 900, color: '#0f172a', lineHeight: 1.2 }}>
              গ্রিন সাইবার ক্যাফে
            </div>
            <div style={{ fontSize: 11, fontWeight: 700, color: '#059669', display: 'flex', alignItems: 'center', gap: 4 }}>
              <span style={{ width: 6, height: 6, borderRadius: '50%', backgroundColor: '#10b981' }} />
              প্রিন্টার অনলাইন ও প্রস্তুত
            </div>
          </div>
        </div>
        <span
          style={{
            fontSize: 10,
            fontWeight: 800,
            padding: '3px 8px',
            borderRadius: 9999,
            backgroundColor: '#f1f5f9',
            color: '#475569',
            border: '1px solid #cbd5e1',
          }}
        >
          সেলফ-সার্ভিস
        </span>
      </div>

      {/* Main Form Scrollable Content */}
      <div style={{ flex: 1, padding: '16px', overflowY: 'hidden', display: 'flex', flexDirection: 'column', gap: 14 }}>
        
        {/* Banner */}
        <div
          style={{
            padding: '10px 14px',
            borderRadius: 16,
            backgroundImage: 'linear-gradient(135deg, #0f172a 0%, #1e293b 100%)',
            color: '#ffffff',
            textAlign: 'center',
          }}
        >
          <div style={{ fontSize: 13, fontWeight: 900 }}>মোবাইল থেকে সরাসরি প্রিন্ট</div>
          <div style={{ fontSize: 10, color: '#94a3b8' }}>ফাইল সিলেক্ট করে পেমেন্ট করলেই প্রিন্ট বের হবে</div>
        </div>

        {/* STEP 1: File Upload Box */}
        <div
          style={{
            padding: 14,
            borderRadius: 20,
            backgroundColor: '#ffffff',
            border: '1.5px solid #e2e8f0',
            boxShadow: '0 2px 8px rgba(0,0,0,0.04)',
          }}
        >
          <div style={{ display: 'flex', justifyContent: 'space-between', marginBottom: 10, alignItems: 'center' }}>
            <span style={{ fontSize: 11, fontWeight: 900, color: '#0f172a', display: 'flex', alignItems: 'center', gap: 6 }}>
              <span style={{ width: 18, height: 18, borderRadius: '50%', backgroundColor: '#f43f5e', color: '#fff', fontSize: 10, display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
                ১
              </span>
              ফাইল নির্বাচন করুন
            </span>
            <span style={{ fontSize: 9, color: '#94a3b8' }}>PDF, DOCX, JPG</span>
          </div>

          <div
            style={{
              padding: '16px',
              borderRadius: 14,
              border: hasFile ? '2px solid #10b981' : '2px dashed #cbd5e1',
              backgroundColor: hasFile ? 'rgba(16,185,129,0.05)' : '#f8fafc',
              textAlign: 'center',
            }}
          >
            {hasFile ? (
              <div>
                <span style={{ fontSize: 28 }}>📄</span>
                <div style={{ fontSize: 13, fontWeight: 900, color: '#059669', marginTop: 4 }}>
                  {fileName}
                </div>
                <div
                  style={{
                    display: 'inline-flex',
                    alignItems: 'center',
                    gap: 4,
                    padding: '2px 8px',
                    borderRadius: 6,
                    backgroundColor: '#d1fae5',
                    color: '#065f46',
                    fontSize: 10,
                    fontWeight: 800,
                    marginTop: 6,
                  }}
                >
                  ✓ {totalPages} পেজ স্বয়ংক্রিয়ভাবে ডিটেক্ট হয়েছে
                </div>
              </div>
            ) : (
              <div>
                <span style={{ fontSize: 24 }}>📤</span>
                <div style={{ fontSize: 12, fontWeight: 700, color: '#475569', marginTop: 4 }}>
                  ফাইল বেছে নিতে এখানে চাপুন
                </div>
              </div>
            )}
          </div>
        </div>

        {/* STEP 2: Print Settings */}
        <div
          style={{
            padding: 14,
            borderRadius: 20,
            backgroundColor: '#ffffff',
            border: '1.5px solid #e2e8f0',
            boxShadow: '0 2px 8px rgba(0,0,0,0.04)',
            display: 'flex',
            flexDirection: 'column',
            gap: 10,
          }}
        >
          <span style={{ fontSize: 11, fontWeight: 900, color: '#0f172a', display: 'flex', alignItems: 'center', gap: 6 }}>
            <span style={{ width: 18, height: 18, borderRadius: '50%', backgroundColor: '#9333ea', color: '#fff', fontSize: 10, display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
              ২
            </span>
            প্রিন্ট সেটিংস
          </span>

          {/* Color vs B&W Selector */}
          <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 8 }}>
            <div
              style={{
                padding: '8px 10px',
                borderRadius: 12,
                border: colorMode === 'bw' ? '2px solid #0f172a' : '1.5px solid #e2e8f0',
                backgroundColor: colorMode === 'bw' ? '#f1f5f9' : '#ffffff',
                display: 'flex',
                justifyContent: 'space-between',
                alignItems: 'center',
              }}
            >
              <div>
                <div style={{ fontSize: 11, fontWeight: 800 }}>সাদাকালো</div>
                <div style={{ fontSize: 9, color: '#64748b' }}>৳৩/পেজ</div>
              </div>
              <input type="radio" checked={colorMode === 'bw'} readOnly style={{ accentColor: '#0f172a' }} />
            </div>

            <div
              style={{
                padding: '8px 10px',
                borderRadius: 12,
                border: colorMode === 'color' ? '2px solid #f43f5e' : '1.5px solid #e2e8f0',
                backgroundColor: colorMode === 'color' ? '#fff1f2' : '#ffffff',
                display: 'flex',
                justifyContent: 'space-between',
                alignItems: 'center',
              }}
            >
              <div>
                <div style={{ fontSize: 11, fontWeight: 800, color: '#e11d48' }}>রঙিন (Color)</div>
                <div style={{ fontSize: 9, color: '#f43f5e' }}>৳১০/পেজ</div>
              </div>
              <input type="radio" checked={colorMode === 'color'} readOnly style={{ accentColor: '#f43f5e' }} />
            </div>
          </div>

          {/* Page count & copies */}
          <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 8 }}>
            <div style={{ padding: '6px 10px', borderRadius: 10, border: '1px solid #e2e8f0', backgroundColor: '#f8fafc' }}>
              <span style={{ fontSize: 9, color: '#64748b', display: 'block', fontWeight: 700 }}>মোট পেজ:</span>
              <span style={{ fontSize: 13, fontWeight: 900 }}>{totalPages}</span>
            </div>
            <div style={{ padding: '6px 10px', borderRadius: 10, border: '1px solid #e2e8f0', backgroundColor: '#f8fafc' }}>
              <span style={{ fontSize: 9, color: '#64748b', display: 'block', fontWeight: 700 }}>কপি সংখ্যা:</span>
              <span style={{ fontSize: 13, fontWeight: 900 }}>{copies} কপি</span>
            </div>
          </div>

          {/* Duplex Checkbox */}
          <div
            style={{
              padding: '6px 10px',
              borderRadius: 10,
              backgroundColor: isDuplex ? 'rgba(244,63,94,0.06)' : '#f8fafc',
              border: isDuplex ? '1px solid #fecdd3' : '1px solid #e2e8f0',
              display: 'flex',
              alignItems: 'center',
              gap: 8,
            }}
          >
            <input type="checkbox" checked={isDuplex} readOnly style={{ accentColor: '#f43f5e' }} />
            <span style={{ fontSize: 11, fontWeight: 800 }}>উভয় পিঠ (Duplex) প্রিন্ট</span>
          </div>
        </div>

        {/* Total Bill Card */}
        <div
          style={{
            padding: '12px 16px',
            borderRadius: 18,
            backgroundColor: '#ffffff',
            border: '2px solid #10b981',
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'space-between',
            boxShadow: '0 4px 12px rgba(16,185,129,0.1)',
          }}
        >
          <div>
            <span style={{ fontSize: 10, fontWeight: 800, color: '#059669', textTransform: 'uppercase' }}>
              স্বয়ংক্রিয় হিসাবকৃত বিল
            </span>
            <div style={{ fontSize: 18, fontWeight: 900, color: '#0f172a' }}>মোট প্রদেয়:</div>
          </div>
          <span style={{ fontSize: 26, fontWeight: 900, color: '#059669' }}>৳{totalBill}</span>
        </div>

        {/* Payment Methods */}
        <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr 1fr', gap: 6 }}>
          <button
            style={{
              padding: '8px 4px',
              borderRadius: 12,
              border: paymentMethod === 'bkash' ? '2px solid #e2136e' : '1px solid #e2e8f0',
              backgroundColor: paymentMethod === 'bkash' ? '#fdf2f8' : '#ffffff',
              fontSize: 11,
              fontWeight: 800,
              color: '#e2136e',
            }}
          >
            বিকাশ
          </button>
          <button
            style={{
              padding: '8px 4px',
              borderRadius: 12,
              border: paymentMethod === 'nagad' ? '2px solid #f97316' : '1px solid #e2e8f0',
              backgroundColor: paymentMethod === 'nagad' ? '#fff7ed' : '#ffffff',
              fontSize: 11,
              fontWeight: 800,
              color: '#ea580c',
            }}
          >
            নগদ
          </button>
          <button
            style={{
              padding: '8px 4px',
              borderRadius: 12,
              border: paymentMethod === 'cash' ? '2px solid #059669' : '1px solid #e2e8f0',
              backgroundColor: paymentMethod === 'cash' ? '#f0fdf4' : '#ffffff',
              fontSize: 11,
              fontWeight: 800,
              color: '#059669',
            }}
          >
            দোকানে ক্যাশ
          </button>
        </div>

        {/* Action Button */}
        <button
          style={{
            padding: '14px',
            borderRadius: 16,
            backgroundImage: 'linear-gradient(135deg, #f43f5e 0%, #e11d48 100%)',
            color: '#ffffff',
            fontSize: 15,
            fontWeight: 900,
            border: 'none',
            boxShadow: '0 8px 20px rgba(244,63,94,0.3)',
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'center',
            gap: 8,
          }}
        >
          <span>🖨️ ওয়ান-ক্লিক প্রিন্ট করুন</span>
        </button>

      </div>

      {/* Floating Success Overlay Modal when submitted */}
      {isSubmitted && (
        <div
          style={{
            position: 'absolute',
            inset: 0,
            backgroundColor: 'rgba(15,23,42,0.85)',
            backdropFilter: 'blur(8px)',
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'center',
            padding: 24,
            zIndex: 100,
          }}
        >
          <div
            style={{
              width: '100%',
              backgroundColor: '#ffffff',
              borderRadius: 28,
              padding: 24,
              textAlign: 'center',
              boxShadow: '0 20px 40px rgba(0,0,0,0.5)',
              display: 'flex',
              flexDirection: 'column',
              alignItems: 'center',
              gap: 12,
            }}
          >
            <div style={{ width: 64, height: 64, borderRadius: '50%', backgroundColor: '#d1fae5', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 32 }}>
              ✓
            </div>
            <div style={{ fontSize: 18, fontWeight: 900, color: '#065f46' }}>
              অর্ডার সফল হয়েছে!
            </div>
            <div style={{ padding: '6px 14px', borderRadius: 9999, backgroundColor: '#f1f5f9', fontSize: 14, fontWeight: 900, fontFamily: 'monospace' }}>
              টোকেন #{jobToken}
            </div>
            <p style={{ fontSize: 12, color: '#475569', margin: 0 }}>
              দোকানের প্রিন্টার থেকে আপনার কাগজ প্রিন্ট হচ্ছে... অনুগ্রহ করে কাউন্টার থেকে সংগ্রহ করুন।
            </p>
          </div>
        </div>
      )}

      {/* Home indicator bar */}
      <div
        style={{
          height: 18,
          display: 'flex',
          alignItems: 'center',
          justifyContent: 'center',
          backgroundColor: '#ffffff',
        }}
      >
        <div style={{ width: 110, height: 4, borderRadius: 2, backgroundColor: '#cbd5e1' }} />
      </div>
    </div>
  );
};
