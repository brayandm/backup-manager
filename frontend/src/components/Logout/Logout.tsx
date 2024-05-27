"use client";

import React from "react";
import { signOut } from "next-auth/react";

const Logout: React.FC = () => {
  return <>{signOut()}</>;
};

export default Logout;
