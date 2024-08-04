"use client";

import React from "react";
import PanelControl from "@/components/PanelControl";
import AssessmentIcon from "@mui/icons-material/Assessment";
import SettingsApplicationsIcon from "@mui/icons-material/SettingsApplications";
import StorageIcon from "@mui/icons-material/Storage";
import ReceiptLongIcon from "@mui/icons-material/ReceiptLong";
import DescriptionIcon from "@mui/icons-material/Description";
import FileCopyIcon from "@mui/icons-material/FileCopy";
import BackupConfigurations from "./BackupConfigurations";
import Overview from "./Overview";
import StorageServers from "./StorageServers";
import Reports from "./Reports";
import DataSources from "./DataSources";
import MigrationConfigurations from "./MigrationConfigurations";

interface DashboardProps {}

function Dashboard({}: DashboardProps) {
  const urlParams = new URLSearchParams(window.location.search);

  const tabMap: { [key: string]: number } = {
    overview: 0,
    "data-sources": 1,
    "storage-servers": 2,
    "backup-configurations": 3,
    "migration-configurations": 4,
    // notifications: 5,
    // reports: 6,
  };

  const tab = urlParams.get("tab");

  const [value, setValue] = React.useState<number>(tab ? tabMap[tab] : 0);
  const [render, setRender] = React.useState<boolean>(false);

  const onChange = (newValue: number) => {
    setValue(newValue);
    const url = new URL(window.location.href);
    const params = new URLSearchParams();

    params.set("tab", Object.keys(tabMap)[newValue]);
    window.history.pushState({}, "", `${url.pathname}?${params.toString()}`);

    setRender(!render);
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
            icon: <DescriptionIcon />,
            label: "Data Sources",
            component: <DataSources />,
          },
          {
            icon: <StorageIcon />,
            label: "Storage Servers",
            component: <StorageServers />,
          },
          {
            icon: <SettingsApplicationsIcon />,
            label: "Backup Configurations",
            component: <BackupConfigurations />,
          },
          {
            icon: <FileCopyIcon />,
            label: "Migration Configurations",
            component: <MigrationConfigurations />,
          },
          // {
          //   icon: <ReceiptLongIcon />,
          //   label: "Reports",
          //   component: <Reports />,
          // },
        ]}
      />
    </div>
  );
}

export default Dashboard;
