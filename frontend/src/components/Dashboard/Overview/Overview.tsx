"use client";

import React from "react";
import { LineChart } from "@mui/x-charts/LineChart";
import { BarChart } from "@mui/x-charts/BarChart";
import { format, subDays, subMonths } from "date-fns";
import { Card, Grid, CardContent, Typography } from "@mui/material";
import { formatBytes } from "@/utils/formatting";
import { PieChart } from "@mui/x-charts";

interface OverviewProps {}

function Overview({}: OverviewProps) {
  const weekBackupData = [5, 3, 6, 2, 8, 4, 7];
  const weekMigrationData = [2, 1, 3, 1, 4, 2, 3];
  const monthBackupData = [10, 6, 12, 4, 16, 8, 14, 20, 18, 22, 24, 26];
  const monthMigrationData = [4, 2, 6, 2, 8, 4, 6, 10, 9, 11, 12, 13];
  const storageServers = [
    {
      name: "Server Malanguita con papelito 1",
      usedSpace: 10,
      freeSpace: 3,
    },
    {
      name: "Server 2",
      usedSpace: 15,
      freeSpace: 15,
    },
    {
      name: "Server 3",
      usedSpace: 5,
      freeSpace: 2,
    },
    {
      name: "Server 4",
      usedSpace: 20,
      freeSpace: 10,
    },
    {
      name: "Server 5",
      usedSpace: 12,
      freeSpace: 2,
    },
  ];
  const summaryData = {
    totalDataSources: 10,
    totalBackups: 20,
    totalMigrations: 8,
    totalMigrationConfigurations: 10,
    totalBackupConfigurations: 15,
    totalStorageServers: 5,
    totalSpaceUsed: 500 * 1024 * 1024 * 1024,
  };

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
        justifyContent: "space-between",
        width: "84vw",
        marginTop: "20px",
      }}
    >
      <div
        style={{
          display: "flex",
          flexDirection: "column",
          gap: "50px",
          justifyContent: "center",
          alignItems: "center",
          padding: "20px",
        }}
      >
        <Grid container spacing={2} justifyContent="center">
          {Object.entries(summaryData).map(([key, value]) => (
            <Grid item xs={12} sm={6} md={4} key={key}>
              <Card sx={{ minwidth: 200 }}>
                <CardContent style={{}}>
                  <Typography variant="subtitle1" component="div">
                    {key
                      .replace(/([A-Z])/g, " $1")
                      .trim()
                      .replace(/\b\w/g, (char) => char.toUpperCase())}
                  </Typography>
                  <Typography variant="h6" component="div">
                    {key === "totalSpaceUsed" ? formatBytes(value) : value}
                  </Typography>
                </CardContent>
              </Card>
            </Grid>
          ))}
        </Grid>
        <Grid container spacing={4} justifyContent="center">
          {storageServers.map((server, index) => (
            <Grid item xs={12} sm={6} md={4} key={index}>
              <div
                style={{
                  display: "flex",
                  flexDirection: "column",
                  gap: "10px",
                  alignItems: "center",
                  justifyContent: "center",
                }}
              >
                <Typography variant="h6" component="div">
                  {server.name}
                </Typography>
                <PieChart
                  series={[
                    {
                      data: [
                        {
                          id: "Used Space",
                          value: server.usedSpace,
                          label: "Used",
                        },
                        {
                          id: "Free Space",
                          value: server.freeSpace,
                          label: "Free",
                        },
                      ],
                    },
                  ]}
                  width={200}
                  height={100}
                />
              </div>
            </Grid>
          ))}
        </Grid>
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
