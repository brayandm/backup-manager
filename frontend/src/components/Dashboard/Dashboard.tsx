"use client";

import React from "react";
import { Typography } from "@mui/material";
import PanelControl from "../PanelControl";
import AssessmentIcon from "@mui/icons-material/Assessment";
import SettingsApplicationsIcon from "@mui/icons-material/SettingsApplications";
import StorageIcon from "@mui/icons-material/Storage";
import ReceiptLongIcon from "@mui/icons-material/ReceiptLong";
import BackupConfigurations from "./BackupConfigurations";
import Overview from "./Overview";
import StorageServers from "./StorageServers";
import Reports from "./Reports";

interface DashboardProps {}

function Dashboard({}: DashboardProps) {
  return (
    <div style={{ display: "flex", flexDirection: "row" }}>
      <PanelControl
        tabs={[
          {
            icon: <AssessmentIcon />,
            label: "Overview",
            component: <Overview />,
          },
          {
            icon: <SettingsApplicationsIcon />,
            label: "Backup Configurations",
            component: <BackupConfigurations />,
          },
          {
            icon: <StorageIcon />,
            label: "Storage Servers",
            component: <StorageServers />,
          },
          {
            icon: <ReceiptLongIcon />,
            label: "Reports",
            component: <Reports />,
          },
        ]}
      />
    </div>
  );
}

export default Dashboard;
