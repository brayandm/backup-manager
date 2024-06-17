"use client";

import React from "react";
import PanelControl from "@/components/PanelControl";
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
  const urlParams = new URLSearchParams(window.location.search);

  const tabMap: { [key: string]: number } = {
    overview: 0,
    "backup-configurations": 1,
    "storage-servers": 2,
    reports: 3,
  };

  const tab = urlParams.get("tab");

  const [value, setValue] = React.useState<number>(tab ? tabMap[tab] : 0);

  const onChange = (newValue: number) => {
    setValue(newValue);
    window.history.pushState({}, "", `?tab=${Object.keys(tabMap)[newValue]}`);
  };

  return (
    <div style={{ display: "flex", flexDirection: "row" }}>
      <PanelControl
        value={value}
        setValue={onChange}
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
