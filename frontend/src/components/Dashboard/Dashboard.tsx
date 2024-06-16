"use client";

import React from "react";
import { Typography } from "@mui/material";
import PanelControl from "../PanelControl";
import AssessmentIcon from "@mui/icons-material/Assessment";
import SettingsApplicationsIcon from "@mui/icons-material/SettingsApplications";
import StorageIcon from "@mui/icons-material/Storage";
import ReceiptLongIcon from "@mui/icons-material/ReceiptLong";

interface DashboardProps {}

function Dashboard({}: DashboardProps) {
  return (
    <div style={{ display: "flex", flexDirection: "row" }}>
      <PanelControl
        tabs={[
          {
            icon: <AssessmentIcon />,
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
            icon: <SettingsApplicationsIcon />,
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
            icon: <StorageIcon />,
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
            icon: <ReceiptLongIcon />,
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
