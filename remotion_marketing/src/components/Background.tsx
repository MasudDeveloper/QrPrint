import React from 'react';
import { interpolate, useCurrentFrame } from 'remotion';

interface BackgroundProps {
  variant?: 'danger' | 'tech' | 'emerald' | 'purple' | 'gold' | 'cyber';
}

export const Background: React.FC<BackgroundProps> = ({ variant = 'tech' }) => {
  const frame = useCurrentFrame();

  // Subtle floating animation for ambient lights
  const orb1Y = interpolate(Math.sin(frame * 0.03), [-1, 1], [-40, 40]);
  const orb1X = interpolate(Math.cos(frame * 0.02), [-1, 1], [-30, 30]);
  const orb2Y = interpolate(Math.cos(frame * 0.025), [-1, 1], [30, -30]);

  // Color schemes based on variant
  let primaryGlow = 'rgba(225, 29, 72, 0.18)'; // Rose
  let secondaryGlow = 'rgba(99, 102, 241, 0.14)'; // Indigo
  let accentGrid = 'rgba(244, 63, 94, 0.05)';

  if (variant === 'danger') {
    primaryGlow = 'rgba(239, 68, 68, 0.28)'; // Red
    secondaryGlow = 'rgba(245, 158, 11, 0.15)'; // Amber
    accentGrid = 'rgba(239, 68, 68, 0.08)';
  } else if (variant === 'emerald') {
    primaryGlow = 'rgba(16, 185, 129, 0.22)'; // Emerald
    secondaryGlow = 'rgba(14, 165, 233, 0.18)'; // Sky
    accentGrid = 'rgba(16, 185, 129, 0.06)';
  } else if (variant === 'purple') {
    primaryGlow = 'rgba(168, 85, 247, 0.22)'; // Purple
    secondaryGlow = 'rgba(236, 72, 153, 0.18)'; // Pink
    accentGrid = 'rgba(168, 85, 247, 0.06)';
  } else if (variant === 'gold') {
    primaryGlow = 'rgba(245, 158, 11, 0.25)'; // Amber/Gold
    secondaryGlow = 'rgba(16, 185, 129, 0.15)'; // Emerald
    accentGrid = 'rgba(245, 158, 11, 0.06)';
  }

  return (
    <div
      style={{
        position: 'absolute',
        inset: 0,
        backgroundColor: '#070b14',
        overflow: 'hidden',
        pointerEvents: 'none',
      }}
    >
      {/* Perspective grid lines */}
      <div
        style={{
          position: 'absolute',
          inset: 0,
          backgroundImage: `linear-gradient(to right, ${accentGrid} 1px, transparent 1px), linear-gradient(to bottom, ${accentGrid} 1px, transparent 1px)`,
          backgroundSize: '80px 80px',
          opacity: 0.7,
        }}
      />

      {/* Primary Floating Glow Orb */}
      <div
        style={{
          position: 'absolute',
          width: 900,
          height: 900,
          borderRadius: '50%',
          top: -150 + orb1Y,
          left: -150 + orb1X,
          background: `radial-gradient(circle, ${primaryGlow} 0%, rgba(0,0,0,0) 70%)`,
          filter: 'blur(100px)',
        }}
      />

      {/* Secondary Floating Glow Orb */}
      <div
        style={{
          position: 'absolute',
          width: 800,
          height: 800,
          borderRadius: '50%',
          bottom: -150 + orb2Y,
          right: -100,
          background: `radial-gradient(circle, ${secondaryGlow} 0%, rgba(0,0,0,0) 70%)`,
          filter: 'blur(90px)',
        }}
      />

      {/* Center Vignette */}
      <div
        style={{
          position: 'absolute',
          inset: 0,
          background: 'radial-gradient(ellipse at center, transparent 40%, rgba(5, 8, 16, 0.85) 100%)',
        }}
      />
    </div>
  );
};
