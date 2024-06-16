"use client";

import { Typography } from "@mui/material";
import React from "react";

interface OverviewProps {}

function Overview({}: OverviewProps) {
  return (
    <Typography variant="h2" sx={{ marginTop: "200px", textAlign: "center" }}>
      Overview Section
    </Typography>
  );
}

export default Overview;
