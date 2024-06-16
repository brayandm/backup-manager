"use client";

import { Typography } from "@mui/material";
import React from "react";

interface ReportsProps {}

function Reports({}: ReportsProps) {
  return (
    <Typography variant="h2" sx={{ marginTop: "200px", textAlign: "center" }}>
      Reports Section
    </Typography>
  );
}

export default Reports;
