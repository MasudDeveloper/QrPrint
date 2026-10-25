import React from 'react';
import { interpolate, spring, useCurrentFrame, useVideoConfig } from 'remotion';

interface MockCursorProps {
  x: number;
  y: number;
  isClicking?: boolean;
  label?: string;
  delay?: number;
}

export const MockCursor: React.FC<MockCursorProps> = ({
  x,
  y,
  isClicking = false,
  label,
  delay = 0,
}) => {
  const frame = useCurrentFrame();
  const { fps } = useVideoConfig();

  const clickScale = isClicking ? 0.85 : 1;
  const rippleAnim = isClicking ? (frame % 20) / 20 : 0;

  return (
    <div
      style={{
        position: 'absolute',
        left: x,
        top: y,
        transform: `translate(-4px, -4px) scale(${clickScale})`,
        pointerEvents: 'none',
        zIndex: 9999,
        transition: 'all 0.15s ease-out',
        display: 'flex',
        alignItems: 'center',
        gap: 8,
      }}
    >
      {/* Click Ripple Effect */}
      {isClicking && (
        <div
          style={{
            position: 'absolute',
            left: 2,
            top: 2,
            width: 36,
            height: 36,
            borderRadius: '50%',
            backgroundColor: 'rgba(244, 63, 94, 0.4)',
            transform: `translate(-50%, -50%) scale(${1 + rippleAnim * 1.5})`,
            opacity: 1 - rippleAnim,
          }}
        />
      )}

      {/* Modern Cursor SVG */}
      <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path
          d="M3 3L10.07 19.97L12.58 12.58L19.97 10.07L3 3Z"
          fill="#f43f5e"
          stroke="#ffffff"
          strokeWidth="2"
          strokeLinejoin="round"
        />
      </svg>

      {/* Optional action tooltip tag */}
      {label && (
        <div
          style={{
            padding: '4px 10px',
            borderRadius: 8,
            backgroundColor: 'rgba(15, 23, 42, 0.9)',
            border: '1px solid rgba(244, 63, 94, 0.4)',
            color: '#ffffff',
            fontSize: 12,
            fontWeight: 700,
            fontFamily: "'Hind Siliguri', sans-serif",
            whiteSpace: 'nowrap',
            boxShadow: '0 4px 12px rgba(0,0,0,0.5)',
          }}
        >
          {label}
        </div>
      )}
    </div>
  );
};
