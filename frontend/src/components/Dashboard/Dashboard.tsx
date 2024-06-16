"use client";

import React from "react";
import { Typography } from "@mui/material";
import PanelControl from "../PanelControl";

interface DashboardProps {}

function Dashboard({}: DashboardProps) {
  return (
    <div style={{ display: "flex", flexDirection: "row" }}>
      <PanelControl
        tabs={[
          {
            icon: <></>,
            label: "Overview",
            component: (
              <Typography
                variant="h2"
                sx={{ marginTop: "200px", textAlign: "center" }}
              >
                Overview Section
              </Typography>
            ),
          },
          {
            icon: <></>,
            label: "Backup Configurations",
            component: (
              <Typography
                variant="h2"
                sx={{ marginTop: "200px", textAlign: "center" }}
              >
                Backup Configurations Section
              </Typography>
            ),
          },
          {
            icon: <></>,
            label: "Storage Servers",
            component: (
              <Typography
                variant="h2"
                sx={{ marginTop: "200px", textAlign: "center" }}
              >
                Storage Servers Section
              </Typography>
            ),
          },
          {
            icon: <></>,
            label: "Reports",
            component: (
              <Typography
                variant="h2"
                sx={{ marginTop: "200px", textAlign: "center" }}
              >
                Reports Section
              </Typography>
            ),
          },
        ]}
      />
    </div>
  );
}

export default Dashboard;
