"use client";

import React from "react";
import { LineChart } from "@mui/x-charts/LineChart";
import { BarChart } from "@mui/x-charts/BarChart";
import { format, subDays, subMonths } from "date-fns";
import { Typography } from "@mui/material";

interface OverviewProps {}

function Overview({}: OverviewProps) {
  const weekBackupData = [5, 3, 6, 2, 8, 4, 7];
  const weekMigrationData = [2, 1, 3, 1, 4, 2, 3];
  const monthBackupData = [10, 6, 12, 4, 16, 8, 14, 20, 18, 22, 24, 26];
  const monthMigrationData = [4, 2, 6, 2, 8, 4, 6, 10, 9, 11, 12, 13];
  const storageServers = [
    {
      name: "Server 1",
      usedSpace: 10,
      freeSpace: 20,
    },
    {
      name: "Server 2",
      usedSpace: 15,
      freeSpace: 15,
    },
    {
      name: "Server 3",
      usedSpace: 5,
      freeSpace: 25,
    },
    {
      name: "Server 4",
      usedSpace: 20,
      freeSpace: 10,
    },
    {
      name: "Server 5",
      usedSpace: 12,
      freeSpace: 18,
    },
  ];

  const today = new Date();
  const xLabelsWeek = Array.from({ length: 7 }, (_, i) =>
    format(subDays(today, i), "EEEE")
  ).reverse();

  const xLabelsMonth = Array.from({ length: 12 }, (_, i) =>
    format(subMonths(today, i), "MMM")
  ).reverse();

  const dayAbbreviations: { [key: string]: string } = {
    Monday: "Mon",
    Tuesday: "Tue",
    Wednesday: "Wed",
    Thursday: "Thu",
    Friday: "Fri",
    Saturday: "Sat",
    Sunday: "Sun",
  };

  const xLabelsAbbreviatedWeek = xLabelsWeek.map(
    (day) => dayAbbreviations[day]
  );

  return (
    <div
      style={{
        display: "flex",
        flexDirection: "row",
        gap: "20px",
      }}
    >
      <div>
        <BarChart
          yAxis={[
            {
              scaleType: "band",
              data: storageServers.map((server) => server.name),
            },
          ]}
          series={[
            {
              data: storageServers.map((server) => server.usedSpace),
              label: "Used Space",
              stack: "A",
            },
            {
              data: storageServers.map((server) => server.freeSpace),
              label: "Free Space",
              stack: "A",
            },
          ]}
          layout="horizontal"
          barLabel="value"
          width={600}
          height={350}
        />
      </div>
      <div
        style={{
          display: "flex",
          flexDirection: "column",
          gap: "20px",
        }}
      >
        <div
          style={{
            display: "flex",
            flexDirection: "column",
            alignItems: "center",
            justifyContent: "center",
            gap: "20px",
          }}
        >
          <Typography variant="h6" gutterBottom>
            Backups & Migrations in the Last Week
          </Typography>
          <LineChart
            width={500}
            height={300}
            series={[
              { data: weekBackupData, label: "Backups" },
              { data: weekMigrationData, label: "Migrations" },
            ]}
            xAxis={[{ scaleType: "point", data: xLabelsAbbreviatedWeek }]}
          />
        </div>
        <div
          style={{
            display: "flex",
            flexDirection: "column",
            alignItems: "center",
            justifyContent: "center",
            gap: "20px",
          }}
        >
          <Typography variant="h6" gutterBottom>
            Backups & Migrations in the Last Month
          </Typography>
          <LineChart
            width={500}
            height={300}
            series={[
              { data: monthBackupData, label: "Backups" },
              { data: monthMigrationData, label: "Migrations" },
            ]}
            xAxis={[{ scaleType: "point", data: xLabelsMonth }]}
          />
        </div>
      </div>
    </div>
  );
}

export default Overview;
