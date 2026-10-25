import React from 'react';

export interface RegisterScreenProps {
  name?: string;
  shopName?: string;
  phone?: string;
  email?: string;
  isSubmitting?: boolean;
}

export const MockRegisterScreen: React.FC<RegisterScreenProps> = ({
  name = 'মো: রাসেল হোসেন',
  shopName = 'গ্রিন সাইবার ক্যাফে & ফটোকপি',
  phone = '01712-345678',
  email = 'greencyber@gmail.com',
  isSubmitting = false,
}) => {
  return (
    <div
      style={{
        width: 1280,
        height: 720,
        backgroundColor: '#fafafa',
        backgroundImage: 'radial-gradient(#d1d5db 1.2px, transparent 1.2px)',
        backgroundSize: '24px 24px',
        borderRadius: 24,
        border: '2px solid #e2e8f0',
        boxShadow: '0 25px 60px -15px rgba(0,0,0,0.4)',
        overflow: 'hidden',
        display: 'flex',
        flexDirection: 'column',
        fontFamily: "'Hind Siliguri', sans-serif",
        color: '#0f172a',
      }}
    >
      {/* Top Browser Bar */}
      <div
        style={{
          height: 44,
          backgroundColor: '#ffffff',
          borderBottom: '1px solid #e2e8f0',
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
            backgroundColor: '#f1f5f9',
            borderRadius: 8,
            border: '1px solid #e2e8f0',
            display: 'flex',
            alignItems: 'center',
            padding: '0 12px',
            fontSize: 12,
            color: '#64748b',
            fontFamily: "'Plus Jakarta Sans', monospace",
            gap: 8,
          }}
        >
          <span style={{ color: '#10b981' }}>🔒</span>
          <span>https://printsheba.com/register</span>
        </div>
      </div>

      {/* Main Container */}
      <div style={{ flex: 1, display: 'flex', flexDirection: 'column', alignItems: 'center', justifyContent: 'center', padding: '20px' }}>
        
        {/* Brand Header */}
        <div style={{ textAlign: 'center', marginBottom: 16, display: 'flex', flexDirection: 'column', alignItems: 'center' }}>
          <div style={{ display: 'flex', alignItems: 'center', gap: 12 }}>
            <div
              style={{
                width: 44,
                height: 44,
                borderRadius: 14,
                backgroundColor: '#e11d48',
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'center',
                color: '#fff',
                fontSize: 22,
                fontWeight: 900,
                boxShadow: '0 8px 18px rgba(225,29,72,0.3)',
              }}
            >
              PS
            </div>
            <div style={{ textAlign: 'left' }}>
              <div style={{ fontSize: 24, fontWeight: 900, fontFamily: "'Plus Jakarta Sans', sans-serif", letterSpacing: '-0.02em', lineHeight: 1 }}>
                Print<span style={{ color: '#f43f5e' }}>Sheba</span>
              </div>
              <span style={{ fontSize: 9, letterSpacing: '0.15em', color: '#94a3b8', fontWeight: 800, textTransform: 'uppercase' }}>
                সাইবার ক্যাফে অটোমেশন
              </span>
            </div>
          </div>
          <h1 style={{ fontSize: 22, fontWeight: 900, color: '#0f172a', margin: '12px 0 0 0' }}>
            নতুন দোকান রেজিস্ট্রেশন
          </h1>
          <p style={{ fontSize: 12, color: '#64748b', margin: '4px 0 0 0' }}>
            মাত্র ১ মিনিটে অ্যাকাউন্ট খুলে আপনার দোকানের নিজস্ব কিউআর কোড তৈরি করুন
          </p>
        </div>

        {/* Registration Card (matches resources/views/auth/register.blade.php) */}
        <div
          style={{
            width: 580,
            backgroundColor: '#ffffff',
            borderRadius: 24,
            padding: '24px 32px',
            border: '1px solid #e2e8f0',
            boxShadow: '0 20px 40px -15px rgba(0,0,0,0.1)',
            display: 'flex',
            flexDirection: 'column',
            gap: 14,
          }}
        >
          {/* Row 1: Name & Shop Name */}
          <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 14 }}>
            <div>
              <label style={{ display: 'block', fontSize: 11, fontWeight: 800, color: '#334155', marginBottom: 6 }}>
                আপনার নাম
              </label>
              <div style={{ padding: '10px 14px', borderRadius: 12, border: '1.5px solid #e2e8f0', backgroundColor: '#f8fafc', fontSize: 13, fontWeight: 700, color: '#0f172a' }}>
                {name}
              </div>
            </div>

            <div>
              <label style={{ display: 'block', fontSize: 11, fontWeight: 800, color: '#334155', marginBottom: 6 }}>
                দোকানের নাম
              </label>
              <div style={{ padding: '10px 14px', borderRadius: 12, border: '1.5px solid #f43f5e', backgroundColor: '#fff', fontSize: 13, fontWeight: 800, color: '#0f172a', boxShadow: '0 0 0 3px rgba(244,63,94,0.15)' }}>
                {shopName}
              </div>
            </div>
          </div>

          {/* Row 2: Phone & Email */}
          <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 14 }}>
            <div>
              <label style={{ display: 'block', fontSize: 11, fontWeight: 800, color: '#334155', marginBottom: 6 }}>
                মোবাইল নম্বর
              </label>
              <div style={{ padding: '10px 14px', borderRadius: 12, border: '1.5px solid #e2e8f0', backgroundColor: '#f8fafc', fontSize: 13, fontWeight: 700, color: '#0f172a', fontFamily: 'monospace' }}>
                {phone}
              </div>
            </div>

            <div>
              <label style={{ display: 'block', fontSize: 11, fontWeight: 800, color: '#334155', marginBottom: 6 }}>
                ইমেইল ঠিকানা
              </label>
              <div style={{ padding: '10px 14px', borderRadius: 12, border: '1.5px solid #e2e8f0', backgroundColor: '#f8fafc', fontSize: 13, fontWeight: 700, color: '#0f172a' }}>
                {email}
              </div>
            </div>
          </div>

          {/* Row 3: Password */}
          <div>
            <label style={{ display: 'block', fontSize: 11, fontWeight: 800, color: '#334155', marginBottom: 6 }}>
              পাসওয়ার্ড
            </label>
            <div style={{ padding: '10px 14px', borderRadius: 12, border: '1.5px solid #e2e8f0', backgroundColor: '#f8fafc', fontSize: 14, letterSpacing: '0.2em', color: '#64748b' }}>
              ••••••••••
            </div>
          </div>

          {/* Submit Button */}
          <div
            style={{
              marginTop: 6,
              padding: '12px',
              borderRadius: 14,
              backgroundColor: isSubmitting ? '#059669' : '#e11d48',
              color: '#ffffff',
              fontSize: 14,
              fontWeight: 900,
              textAlign: 'center',
              boxShadow: '0 8px 20px rgba(225,29,72,0.3)',
              cursor: 'pointer',
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
              gap: 8,
            }}
          >
            {isSubmitting ? (
              <span>✓ অ্যাকাউন্ট সফলভাবে তৈরি হচ্ছে...</span>
            ) : (
              <span>🚀 বিনামূল্যে অ্যাকাউন্ট তৈরি করুন</span>
            )}
          </div>
        </div>
      </div>
    </div>
  );
};
