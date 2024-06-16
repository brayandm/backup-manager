"use client";

import { Typography } from "@mui/material";
import React from "react";

interface StorageServersProps {}

function StorageServers({}: StorageServersProps) {
  return (
    <Typography variant="h2" sx={{ marginTop: "200px", textAlign: "center" }}>
      Storage Servers Section
    </Typography>
  );
}

export default StorageServers;
