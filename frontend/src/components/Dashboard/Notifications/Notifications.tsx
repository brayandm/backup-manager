"use client";

import { Typography } from "@mui/material";
import React from "react";

interface NotificationsProps {}

function Notifications({}: NotificationsProps) {
  return (
    <Typography variant="h2" sx={{ marginTop: "200px", textAlign: "center" }}>
      Notifications Section
    </Typography>
  );
}

export default Notifications;
